@props([
    'color'              => 'var(--bg-color)',
    'animation'          => 'none',
    'leftApperarm'       => '',
    'leftForearm'        => '',
    'rightApperarm'      => '',
    'rightForearm'       => '',
    'legsContainer'      => '',
    'leftLeg'            => '',
    'rightLeg'           => '',
])

<div {{ $attributes->merge(['class' => 'robot_container','style' => "color: $color; animation: $animation; filter: url(#shadow);"]) }}>
    <div class="robot_antenna">
        <div></div>
        <div></div>
    </div>
    <div class="robot_head_container">
        <div class="robot_ear"></div>
        <div class="robot_head">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="robot_ear"></div>
    </div>
    <div class="robot_body_container">
        <div class="robot_apperarm_container">
            <div class="robot_shoulder left"></div>
            <div class="robot_apperarm left" style="{{ $leftApperarm }}">
                <div class="robot_elbow left">
                    <div class="robot_forearm left" style="{{ $leftForearm }}">
                        <div class="robot_hand left">{{ $leftHand ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="robot_body">
            <div></div>
            <div></div>
        </div>
        <div class="robot_apperarm_container">
            <div class="robot_shoulder right"></div>
            <div class="robot_apperarm right" style="{{ $rightApperarm }}">
                <div class="robot_elbow right">
                    <div class="robot_forearm right" style="{{ $rightForearm }}">
                        <div class="robot_hand right"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="robot_legs_container" style="{{ $legsContainer }}">
        <div class="robot_leg" style="{{ $leftLeg }}"><div class="robot_foot"></div></div>
        <div class="robot_leg" style="{{ $rightLeg }}"><div class="robot_foot"></div></div>
    </div>
</div>