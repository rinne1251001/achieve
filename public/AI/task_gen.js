const featureRules = {
  "やりたいことがわからない": ["doubt", "want_unknown"],
  "自信がない": ["anxiety", "no_confidence"],
  "行動できない": ["doubt", "unable_action"],
  "将来が不安": ["doubt","future_anxiety"],

  "何から始めればいいかわからない": ["doubt","start_unknown"],
  "モチベーションが低い": ["anxiety","low_motivation"],
  "情報が多すぎて迷う": ["doubt","too_much_infomation"],

  "興味の方向性が見える": ["step","see_interest"],
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

function extractFeatures(answers) {
  const features = [];

  for (const answer of answers) {
    if (featureRules[answer]) {
      features.push(...featureRules[answer]);
    }
  }

  return [...new Set(features)];
}
// ① 検索して pageid を取得
async function searchWiki(query) {
  const url = `https://ja.wikipedia.org/w/api.php?action=query&list=search&srsearch=${encodeURIComponent(query)}&format=json&origin=*`;
  const res = await fetch(url);
  const data = await res.json();
  return data.query.search; // [{title, pageid, snippet}, ...]
}

// ② pageid からページ構造を取得
async function getWikiStructure(pageid) {
  const url = `https://ja.wikipedia.org/w/api.php?action=parse&pageid=${pageid}&prop=sections|links|categories&format=json&origin=*`;
  const res = await fetch(url);
  const data = await res.json();

  return {
    sections: data.parse.sections || [],
    links: data.parse.links || [],
    categories: data.parse.categories || []
  };
}

// ③ チェック結果 → 状態構造（SPM）
function buildStateFromForm() {
  const checks = document.querySelectorAll('#questionForm input[type="checkbox"]:checked');
  const counts = { doubt: 0, anxiety: 0, step: 0, social: 0 };
  const raw = [];

  checks.forEach(c => {
    counts[c.name] += 1;
    raw.push(c.value);
  });

  let dominant = null;
  let max = 0;
  Object.entries(counts).forEach(([k, v]) => {
    if (v > max) {
      max = v;
      dominant = k;
    }
  });

  return {
    counts,
    dominantCategory: dominant,
    rawChecks: raw,
    totalChecks: checks.length
  };
}

// ④ Wiki構造 → 意味構造へ変換
function buildMeaningStructure(structure) {
  const mainSections = structure.sections
    .filter(s => s.toclevel === 1)
    .map(s => s.line);

  const relatedConcepts = structure.links
    .filter(l => l.ns === 0)
    .slice(0, 10)
    .map(l => l['*']);

  const categories = structure.categories.map(c => c['*']);

  return {
    mainSections,
    relatedConcepts,
    categories
  };
}
function abstractSectionName(name) {
  if (name.includes("定義")) return "このテーマがどう説明されているか";
  if (name.includes("種類")) return "どんなタイプがあるか";
  if (name.includes("関係")) return "自分の行動とどうつながるか";
  if (name.includes("歴史")) return "このテーマがどう発展してきたか";
  return "このテーマを自分なりに理解するポイント";
}
function abstractMeaning(meaning) {
  return meaning.mainSections.map(abstractSectionName);
}
// ⑤ 意味構造からタスク生成
function generateTaskFromMeaning(coreWord, meaning) {
  const abstracted = abstractMeaning(meaning);

  const bigTask =
    `「${coreWord}」について、${abstracted.join(" / ")} の中から気になるものを1つ選び、自分の言葉で3〜5行にまとめる`;

  const smallTasks = [
    `今日の生活の中で「${coreWord}」を感じた瞬間を1つ思い出す`,
    `その瞬間がどんな種類の「${coreWord}」だったか自分なりに分類する`,
    `その「${coreWord}」がどんな行動につながりそうかを1行で書く`
  ];

  return { bigTask, smallTasks };
}

// ⑥ 全体フロー
document.getElementById('submit').addEventListener('click', async (event) => {
  event.preventDefault();

  const state = buildStateFromForm();

  const queryMap = {
    doubt: "興味",
    anxiety: "不安",
    step: "自己成長",
    social: "自己肯定感"
  };

  const query = queryMap[state.dominantCategory] || "心理学";

  const results = await searchWiki(query);
  if (results.length === 0) return;

  const pageid = results[0].pageid;
  const coreWord = results[0].title;

  const structure = await getWikiStructure(pageid);

  const meaning = buildMeaningStructure(structure);

  const task = generateTaskFromMeaning(coreWord, meaning);

  document.getElementById('result').innerHTML = `
    <h3>大きなタスク</h3>
    <p>${task.bigTask}</p>

    <h3>小さなタスク（3ステップ）</h3>
    <ol>
      ${task.smallTasks.map(t => `<li>${t}</li>`).join("")}
    </ol>
  `;
});