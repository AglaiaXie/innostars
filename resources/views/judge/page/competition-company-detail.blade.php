@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
    <div class="hero is-info">
        @include('judge.partial.hero')
    </div>
@endsection

@section('content')
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="{{ url('/judge/competitions') }}">Competitions</a></li>
                <li class="is-active"><a href="#" aria-current="page">{{$company->company->name}}</a></li>
            </ul>
        </nav>
        <div class="columns">
            <div class="column is-10 is-offset-1">
                <h4 class="title is-4">Company Information</h4>
                <hr>
                @include('participant.partial.profile.show.company', ['participant' => $company->company->user])

                <br>
                <h4 class="title is-4">Contact Information</h4>
                <hr>
                @include('participant.partial.profile.show.contact', ['participant' => $company->company->user])

                <br>
                <h4 class="title is-4">Project Information</h4>
                <hr>
                @include('participant.partial.profile.show.project', ['participant' => $company->company->user])

                <br>
                <h4 class="title is-4">Additional Information</h4>
                <hr>
                @include('participant.partial.profile.show.addition', ['participant' => $company->company->user])

                <br>
                <h4 class="title is-4">Documents</h4>
                <hr>
                @include('participant.partial.profile.show.file', ['participant' => $company->company->user])
            </div>
        </div>
        <hr>
        <h3 class="title is-3">Your Score</h3>
        <form method="post" id="scoreForm" class="form-horizontal">
            {{ csrf_field() }}
            {{ method_field('PUT') }}
            <table class="table is-bordered is-fullwidth is-striped is-hoverable">
                <thead>
                <tr>
                    <th>
                        Criteria
                    </th>
                    @for($i = 5; $i > 0; $i--)
                        <td>{{ $i }}</td>
                    @endfor
                </tr>
                </thead>
                <tbody>

                @if ($competition->name === \App\Models\Competition::NAME_ONLINE)
                    <tr>
                        <th rowspan="2">
                            Pain Point
                        </th>
                        <td colspan="10">
                            Info about the problem & opportunity
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="pain_point"
                                       value="{{ $i }}"{{ old('pain_point') == $i ? ' checked': object_get($score, 'pain_point') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'pain_point') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Value Proposition
                        </th>
                        <td colspan="10">
                            Product/service info and how it will solve the problem or take advantage of the opportunity
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="value_proposition"
                                       value="{{ $i }}"{{ old('value_proposition') == $i ? ' checked': object_get($score, 'value_proposition') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'value_proposition') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Market Analysis
                        </th>
                        <td colspan="10">
                            Target market, market size, competitive analysis
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="market_analysis"
                                       value="{{ $i }}"{{ old('market_analysis') == $i ? ' checked': object_get($score, 'market_analysis') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'market_analysis') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Financial Model
                        </th>
                        <td colspan="10">
                            Revenue Streams, Cost structure
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="financial_model"
                                       value="{{ $i }}"{{ old('financial_model') == $i ? ' checked': object_get($score, 'financial_model') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'financial_model') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Expertise
                        </th>
                        <td colspan="10">
                            Leadership team and background
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="expertise"
                                       value="{{ $i }}"{{ old('expertise') == $i ? ' checked': object_get($score, 'expertise') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'expertise') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>
                @endif

                @if ($competition->name === \App\Models\Competition::NAME_PRELIMINARY_STAGE)
                    <tr>
                        <th rowspan="2">
                            Target Market
                        </th>
                        <td colspan="10">
                            Clearly defined the market? Stable or high growth? Mass or niche market?
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="target_market"
                                       value="{{ $i }}"{{ old('target_market') == $i ? ' checked': object_get($score, 'target_market') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'target_market') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Solution
                        </th>
                        <td colspan="10">
                            Uniqueness, efficiency, convenience, price advantage
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="solution"
                                       value="{{ $i }}"{{ old('solution') == $i ? ' checked': object_get($score, 'solution') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'solution') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Competitive advantage
                        </th>
                        <td colspan="10">
                            Comparison to alternatives; management assessment of challenges and barrier either technical
                            or commercial
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="competitive_advantage"
                                       value="{{ $i }}"{{ old('competitive_advantage') == $i ? ' checked': object_get($score, 'competitive_advantage') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'competitive_advantage') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Team, Board, Advisers
                        </th>
                        <td colspan="10">
                            Industry knowledge, unique skills, leadership, key relationships, coachability
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="team_board_adviser"
                                       value="{{ $i }}"{{ old('team_board_adviser') == $i ? ' checked': object_get($score, 'team_board_adviser') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'team_board_adviser') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Financial
                        </th>
                        <td colspan="10">
                            Revenue, cost, $raised
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="financial"
                                       value="{{ $i }}"{{ old('financial') == $i ? ' checked': object_get($score, 'financial') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'financial') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Exit Opportunity
                        </th>
                        <td colspan="10">
                            Potential buyers? IPO? Buy vs. Build?
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="exit_opportunity"
                                       value="{{ $i }}"{{ old('exit_opportunity') == $i ? ' checked': object_get($score, 'exit_opportunity') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'exit_opportunity') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Strategic Value
                        </th>
                        <td colspan="10">
                            Overall
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="strategic_value"
                                       value="{{ $i }}"{{ old('strategic_value') == $i ? ' checked': object_get($score, 'strategic_value') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'strategic_value') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Spoke Clearly
                        </th>
                        <td colspan="10">
                            Is the presentation clear and comprehensive?
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="spoke_clearly"
                                       value="{{ $i }}"{{ old('spoke_clearly') == $i ? ' checked': object_get($score, 'spoke_clearly') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'spoke_clearly') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Attitude
                        </th>
                        <td colspan="10">
                            Passionate about the idea?
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="attitude"
                                       value="{{ $i }}"{{ old('attitude') == $i ? ' checked': object_get($score, 'attitude') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'attitude') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="2">
                            Relate to Audience
                        </th>
                        <td colspan="10">
                            Capture audiances' attention?
                        </td>
                    </tr>
                    <tr>
                        @for($i = 10; $i > 0; $i--)
                            <td>
                                <input type="radio" name="relate_to_audience"
                                       value="{{ $i }}"{{ old('relate_to_audience') == $i ? ' checked': object_get($score, 'relate_to_audience') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'relate_to_audience') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>
                @endif

                @if ($competition->name === \App\Models\Competition::NAME_FINAL)
                    <tr>
                        <th rowspan="10">
                            产品与服务 Product/Service （25）
                        </th>
                        <td colspan="5">
                            - 产品和技术具有明显的创新性，技术领先 <br>
                            - The technology/product can solve a problem in an innovative way and demonstrate clear advantages
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_0"
                                       value="{{ $i }}"{{ old('final_0') == $i ? ' checked': object_get($score, 'final_0') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_0') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 产品成熟度较高（实验室研发、小试、中试、量产） <br>
                            - High maturity ( R&D at laboratory/small test/test/ mass production)
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_1"
                                       value="{{ $i }}"{{ old('final_1') == $i ? ' checked': object_get($score, 'final_1') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_1') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 具有一定技术或资源壁垒，进入门槛高 <br>
                            - With technical or resource barriers, high entry threshold
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_2"
                                       value="{{ $i }}"{{ old('final_2') == $i ? ' checked': object_get($score, 'final_2') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_2') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 中国尚未有同类竞品，或与中国竞品相比具有综合优势 <br>
                            - In China no similar products, or have a comprehensive advantage compared with local competitors
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_3"
                                       value="{{ $i }}"{{ old('final_3') == $i ? ' checked': object_get($score, 'final_3') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_3') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 知识产权拥有和申请情况 <br>
                            - IP and patent situation
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_4"
                                       value="{{ $i }}"{{ old('final_4') == $i ? ' checked': object_get($score, 'final_4') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_4') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="6">
                            商业模式 Business model （15）
                        </th>
                        <td colspan="5">
                            - 盈利模式清晰，发展规划合理，在中国市场发展思路明确 <br>
                            - Clear profit model, reasonable development plan in Chinese market
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_5"
                                       value="{{ $i }}"{{ old('final_5') == $i ? ' checked': object_get($score, 'final_5') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_5') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 商业模式具有独特性，不易被复制 <br>
                            - Business models are unique and difficult to replicate
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_6"
                                       value="{{ $i }}"{{ old('final_6') == $i ? ' checked': object_get($score, 'final_6') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_6') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 在中国市场中，盈利模式/合作方式具有较强的可执行性 <br>
                            - The profit model/cooperation method can be easily carried out In the Chinese market
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_7"
                                       value="{{ $i }}"{{ old('final_7') == $i ? ' checked': object_get($score, 'final_7') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_7') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="6">
                            公司团队 Company team（15）
                        </th>
                        <td colspan="5">
                            - 核心团队成员具有相关领域的技术及市场经验 <br>
                            - Core team members have technical and market experience in related fields
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_8"
                                       value="{{ $i }}"{{ old('final_8') == $i ? ' checked': object_get($score, 'final_8') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_8') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 核心团队成员稳定 <br>
                            - Stable Core team members
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_9"
                                       value="{{ $i }}"{{ old('final_9') == $i ? ' checked': object_get($score, 'final_9') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_9') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 公司人员队伍持续增长 <br>
                            - The number of company's staff continues to grow
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_10"
                                       value="{{ $i }}"{{ old('final_10') == $i ? ' checked': object_get($score, 'final_10') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_10') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="6">
                            市场情况 Market situation（15）
                        </th>
                        <td colspan="5">
                            - 产品/服务已在市场上取得了成绩 <br>
                            - Products/services have achieved success in the market
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_11"
                                       value="{{ $i }}"{{ old('final_11') == $i ? ' checked': object_get($score, 'final_11') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_11') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 中国市场需求明显 <br>
                            - Strong demand in Chinese market
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_12"
                                       value="{{ $i }}"{{ old('final_12') == $i ? ' checked': object_get($score, 'final_12') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_12') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 未来市场前景广阔 <br>
                            - Excellent market prospects
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_13"
                                       value="{{ $i }}"{{ old('final_13') == $i ? ' checked': object_get($score, 'final_13') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_13') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="6">
                            财务与融资 Financing and investment（15）
                        </th>
                        <td colspan="5">
                            - 有明确的财务发展计划（营收、利润、现金流等） <br>
                            - Provide convincing financial forecasts such as revenue, cost, cash flow and capital needs
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_14"
                                       value="{{ $i }}"{{ old('final_14') == $i ? ' checked': object_get($score, 'final_14') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_14') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 融资需求明确、用途清晰，估值合理 <br>
                            - Clear financing needs, clear use and reasonable valuation
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_15"
                                       value="{{ $i }}"{{ old('final_15') == $i ? ' checked': object_get($score, 'final_15') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_15') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 投资机构资金退出渠道清晰 <br>
                            - Clear channels for funds withdraw
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_16"
                                       value="{{ $i }}"{{ old('final_16') == $i ? ' checked': object_get($score, 'final_16') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_16') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <th rowspan="6">
                            赛场表现 Roadshow performance（15）
                        </th>
                        <td colspan="5">
                            - 展示充分，清晰的表达了公司的核心诉求 <br>
                            - Demonstrate the company's core demands with full and clear expression
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_17"
                                       value="{{ $i }}"{{ old('final_17') == $i ? ' checked': object_get($score, 'final_17') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_17') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 善于沟通，对评委嘉宾的提问能够清楚解答 <br>
                            - Good communication and clear answers to the questions of the jury
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_18"
                                       value="{{ $i }}"{{ old('final_18') == $i ? ' checked': object_get($score, 'final_18') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_18') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>

                    <tr>
                        <td colspan="5">
                            - 富有激情，能够感染评委及现场投资人 <br>
                            - Passionate, able to infect judges and investors
                        </td>
                    </tr>
                    <tr>
                        @for($i = 5; $i > 0; $i--)
                            <td>
                                <input type="radio" name="final_19"
                                       value="{{ $i }}"{{ old('final_19') == $i ? ' checked': object_get($score, 'final_19') == $i ? ' checked' : '' }}
                                        {{ $score->submit && object_get($score, 'final_19') != $i ? ' hidden' : '' }}>
                            </td>
                        @endfor
                    </tr>
                @endif
                </tbody>
            </table>

            <div class="field">
                <label class="label">对该项目的发展建议：Recommendations for the project:</label>
                <div class="control">
                    <textarea class="textarea" name="comment"
                              rows="5"{{ $score->submit ? ' disabled' : '' }}>{{ $score->comment }}</textarea>
                </div>
            </div>

            @if(!$score->submit)
                <article class="message is-danger">
                    <div class="message-body">
                        Read before you click！If you don't want to submit the score right now, please click "<strong>Save
                            Draft</strong>". If you want to submit the score now, please click "<strong>Submit</strong>"
                        and you can no longer edit
                        your score after submission.
                    </div>
                </article>
                <div class="field is-grouped">
                    <div class="control">
                        <input type="hidden" id="isSubmit" name="is_submit" value="1"/>
                        <button type="button" class="button is-primary" onclick="saveDraft()">Save Draft</button>
                    </div>
                    <div class="control">
                        <button type="submit" class="button is-link">Submit</button>
                    </div>
                </div>
            @else
                <div class="columns" id="resultMessage">
                    <div class="column is-one-quarter is-offset-5">
                        <div class="notification is-success">
                            Your score is submitted successfully.
                        </div>
                    </div>
                </div>
            @endif
        </form>
    <script>
        function saveDraft() {
            $('#isSubmit').val(0);
            $('#scoreForm').submit();
        }
    </script>
@endsection