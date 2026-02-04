// ===============================
// 1. featureRules：選択肢 → [カテゴリ, 特徴キー]
// ===============================
export const featureRules = {
    "やりたいことがわからない": ["doubt", "want_unknown"],
    "自信がない": ["anxiety", "no_confidence"],
    "行動できない": ["doubt", "unable_action"],
    "将来が不安": ["doubt","future_anxiety"],

    "何から始めればいいかわからない": ["doubt","start_unknown"],
    "モチベーションが低い": ["anxiety","low_motivation"],
    "情報が多すぎて迷う": ["doubt","too_much_information"],

    "やりたいことがわかる": ["step","see_interest"],
    "自信がつく": ["step","gain_confidence"],
    "行動できる": ["step","move"],

    "失敗が怖い": ["anxiety","fear_failure"],
    "続かない": ["anxiety","cant_continue"],
    "他人と比べてしまう": ["social","compare_others"],
    "人前で話すのが苦手": ["social","hate_speaking"],

    "コツコツ続けられる": ["step","like_consistency"],
    "好奇心がある": ["social","curious"],
    "人の話を聞ける": ["social","listen_to_others"]
};

// ===============================
// 2. カテゴリ → Wiki検索ワード
// ===============================
const queryMap = {
    doubt: "興味",
    anxiety: "不安",
    step: "自己成長",
    social: "自己肯定感"
};

// ===============================
// 3. Wiki検索
// ===============================
async function searchWiki(query) {
    const url = `https://ja.wikipedia.org/w/api.php?action=query&list=search&srsearch=${encodeURIComponent(query)}&format=json&origin=*`;
    const res = await fetch(url);
    const data = await res.json();
    return data.query.search;
}

// ===============================
// 4. Wikiページ構造取得
// ===============================
// ===============================
// 4. Wikiページ構造取得（安全版）
// ===============================
async function getWikiStructure(pageid) {
    const url = `https://ja.wikipedia.org/w/api.php?action=parse&pageid=${pageid}&prop=sections|links|categories&format=json&origin=*`;
    const res = await fetch(url);
    const data = await res.json();

    // data.parse が存在するかチェック
    if (!data.parse) {
        return { sections: [], links: [], categories: [] };
    }

    return {
        sections: data.parse.sections || [],
        links: data.parse.links || [],
        categories: data.parse.categories || []
    };
}

// ===============================
// 5. Wiki構造 → 抽象化（人間向け）
// ===============================
function abstractSectionName(name) {
    if (name.includes("定義")) return "このテーマがどう説明されているか";
    if (name.includes("種類")) return "どんなタイプがあるか";
    if (name.includes("関係")) return "自分の行動とどうつながるか";
    if (name.includes("歴史")) return "どのように発展してきたか";
    return "このテーマを理解するためのポイント";
}

function buildMeaningStructure(structure) {
    const mainSections = structure.sections
        .filter(s => s.toclevel === 1)
        .map(s => abstractSectionName(s.line));

    const relatedConcepts = structure.links
        .filter(l => l.ns === 0)
        .slice(0, 5)
        .map(l => l['*']);

    return { mainSections, relatedConcepts };
}

// ===============================
// 6. タスク生成（Wikiを読ませない）
// ===============================
function generateBigTask(coreWord, meaning) {
    return `「${coreWord}」について、${meaning.mainSections.join(" / ")} の中から気になるものを1つ選び、自分の言葉で3〜5行でまとめる`;
}

function generateSmallTasks(coreWord, meaning) {
    return [
        `今日の生活の中で「${coreWord}」を感じた瞬間を1つ思い出す`,
        `その瞬間がどんな意味を持っていたか1〜2行で書く`,
        `その気づきをもとに、明日できる小さな行動を1つ決める`
    ];
}

// ===============================
// 7. タスクツリー（階層構造）
// ===============================
function buildTaskTree(bigTask, smallTasks) {

    // smallTasks が 3 つ未満なら補完
    while (smallTasks.length < 3) {
        smallTasks.push("明日できる小さな行動を1つ決める");
    }

    return {
        title: bigTask,
        children: smallTasks.map(t => ({
            title: t,
            children: [{ title: t }]
        }))
    };
}

// ===============================
// 8. メイン処理（安全版）
// ===============================
export async function processChecklist(answerList) {
    // --- カテゴリ集計 (省略) ---
    const counts = { doubt: 0, anxiety: 0, step: 0, social: 0 };
    answerList.forEach(ans => {
        if (featureRules[ans]) {
            const [cat] = featureRules[ans];
            counts[cat] += 1;
        }
    });
    const dominant = Object.entries(counts).sort((a,b)=>b[1]-a[1])[0][0];
    const query = queryMap[dominant] || "心理学";

    // --- Wiki検索 ---
    const results = await searchWiki(query);

    // 【修正ポイント】検索結果が空の場合のハンドリング
    if (!results || results.length === 0) {
        return {
            bigTask: "自分自身について考える時間を取る",
            smallTasks: ["深呼吸する", "今の気持ちを書く", "明日の予定を決める"],
            taskTree: { title: "自分を見つめる", children: [] }
        };
    }

    const pageid = results[0].pageid;
    const coreWord = results[0].title;

    // --- Wiki構造取得 ---
    const structure = await getWikiStructure(pageid);

    // --- 抽象化 ---
    const meaning = buildMeaningStructure(structure);

    // --- タスク生成 ---
    const bigTask = generateBigTask(coreWord, meaning);
    const smallTasks = generateSmallTasks(coreWord, meaning);

    // --- タスクツリー ---
    const taskTree = buildTaskTree(bigTask, smallTasks);

    return { bigTask, smallTasks, taskTree };
}