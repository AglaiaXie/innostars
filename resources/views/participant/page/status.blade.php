@extends('laravel-bulma-starter::layouts.bulma')

@section('hero')
  <div class="hero is-info">
    @include('participant.partial.hero')
  </div>
@endsection

@section('content')
    <div class="columns">
      <div class="column is-10 is-offset-1">
        <ul class="steps is-horizontal has-content-centered has-gaps">
          <li class="steps-segment{{ object_get($online_stage, 'promoted') ? '' : ' is-active'}} ">
            <span href="#"
                  class="steps-marker{{ object_get($participant, 'company.approval') ? '' : ' is-hollow' }}">1</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Application Registration
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    @if(object_get($participant, 'company.submit'))
                      <p>
                        <icon class="icon has-text-success"><i class="fa fa-check"></i></icon>
                        Application Submitted
                      </p>
                    @else
                      <p>Please complete & submit your application and wait for admin approval.</p>
                    @endif
                    @if(object_get($participant, 'company.approval'))
                      <p>
                        <icon class="icon has-text-success"><i class="fa fa-check"></i></icon>
                        Application Approved, qualified for Online Competition
                      </p>
                    @endif
                    @if(object_get($online_stage, 'promoted'))
                      <p>
                        <icon class="icon has-text-success"><i class="fa fa-check"></i></icon>
                        You passed the Online Competition
                      </p>
                    @endif
                  </div>
                </div>
                <footer class="card-footer">
                  @if(!object_get($participant, 'company.approval') && !object_get($participant, 'company.submit'))
                    <a href="{{url('/participant/profile/company')}}" class="card-footer-item">Application Form</a>
                  @endif
                  @if(object_get($participant, 'company.approval'))
                    <p class="card-footer-item">
                      @if($online_scores->count())
                        <a href="#" onclick="$('#onlineScoresModal').addClass('is-active')">Scores</a>

                      @else
                        Scores
                      @endif
                    </p>
                    <p class="card-footer-item">
                      @if($online_scores->count())
                        <a href="#" onclick="$('#onlineCommentModal').addClass('is-active')">Comments</a>
                      @else
                        Comments
                      @endif
                    </p>
                  @endif
                </footer>
              </div>
            </div>
          </li>
          <li class="steps-segment{{ object_get($preliminary_stage, 'promoted') ? '' : ' is-active'}}">
            <span href="#"
                  class="steps-marker{{ object_get($preliminary_stage, 'promoted') ? '' : ' is-hollow'}}">2</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Preliminary Stage
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    <p>Pitch (Presentation + Q&A) in the U.S. cities</p>
                    @if(object_get($preliminary_stage, 'promoted') && object_get($preliminary_stage, 'industry_rank') > 0 && object_get($preliminary_stage, 'industry_rank') < 4)
                      <p>
                        <icon class="icon has-text-success"><i class="fa fa-check"></i></icon>
                        Qualified for Preliminary Stage Competition in {{ $preliminary_stage->competition->city }}</p>
                      @if($preliminary_stage->competition->city === 'Chicago')
                        <b>Congratulations!</b> You are  qualified for US China Innovation and Investment Summit in Houston.
                        Check <a href="http://uschinainnovation.org/ucis2018/">US China Innovation and Investment Summit website</a> 1.
                        One free admission (worth $580) 2. One-on-one B2B matchmaking meetings with potential Chinese
                        investors and partners.
                      @endif
                    @endif
                    @if(object_get($preliminary_stage, 'promoted'))
                      <p>
                        <icon class="icon has-text-success"><i class="fa fa-check"></i></icon>
                        You passed the Preliminary Competition
                      </p>
                    @endif
                  </div>
                </div>
                <footer class="card-footer">
                  @if(object_get($online_stage, 'promoted'))
                    <p class="card-footer-item">
                      @if($preliminary_scores->count())
                        <a href="#" onclick="$('#preliminaryScoresModal').addClass('is-active')">Scores</a>

                      @else
                        Scores
                      @endif
                    </p>
                    <p class="card-footer-item">
                      @if($preliminary_scores->count())
                        <a href="#" onclick="$('#preliminaryCommentsModal').addClass('is-active')">Comments</a>
                      @else
                        Comments
                      @endif
                    </p>
                  @endif
                </footer>
              </div>
            </div>
          </li>
          <li class="steps-segment is-active">
            <span href="#" class="steps-marker is-hollow">3</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Semifinal
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    <p>Pitch and roadshows in multiple cities in China</p>
                    @if(object_get($preliminary_stage, 'promoted'))
                      <p><semifinal-form :id="{{ $participant->id }}" :form_id="{{ $semifinal_form->id }}"></semifinal-form></p>
                    @endif
                  </div>
                </div>
                <footer class="card-footer">
                </footer>
              </div>
            </div>
          </li>
          <li class="steps-segment is-active">
            <span href="#" class="steps-marker is-hollow">4</span>
            <div class="steps-content column">
              <div class="card">
                <header class="card-header">
                  <p class="card-header-title">
                    Grand Final
                  </p>
                </header>
                <div class="card-content">
                  <div class="content">
                    @if(object_get($preliminary_stage, 'promoted'))
                      <p><final-form :id="{{ $participant->id }}" :form_id="{{ $final_form->id }}"></final-form></p>
                    @endif
                  </div>
                </div>
                <footer class="card-footer">
                </footer>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  @if($online_scores->count())
    <div class="modal" id="onlineScoresModal">
      <div class="modal-background" onclick="$('#onlineScoresModal').removeClass('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Online Competition Scores</p>
          <button class="delete" aria-label="close" onclick="$('#onlineScoresModal').removeClass('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <table class="table is-bordered is-fullwidth is-striped is-hoverable">
            <thead>
            <th>Criteria</th>
            <th>Score</th>
            </thead>
            <tbody>
            <tr>
              <th>Pain Point</th>
              <td>{{ number_format($online_scores->avg('pain_point'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Value Proposition</th>
              <td>{{ number_format($online_scores->avg('value_proposition'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Market Analysis</th>
              <td>{{ number_format($online_scores->avg('market_analysis'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Financial Model</th>
              <td>{{ number_format($online_scores->avg('financial_model'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Expertise</th>
              <td>{{ number_format($online_scores->avg('expertise'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Total Score</th>
              <th>{{ number_format($online_scores->avg('total_score'), 1) + 0 }}</th>
            </tr>
            </tbody>
          </table>
        </section>
      </div>
    </div>
    <div class="modal" id="onlineCommentModal">
      <div class="modal-background" onclick="$('#onlineCommentModal').removeClass('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Online Competition Comments</p>
          <button class="delete" aria-label="close"
                  onclick="$('#onlineCommentModal').removeClass('is-active')"></button>
        </header>
        <section class="modal-card-body">
          @foreach($online_scores as $online_score)
            <article class="message is-dark">
              <div class="message-body">
                {{ $online_score->comment }}
              </div>
            </article>
          @endforeach
        </section>
      </div>
    </div>
  @endif
  @if($preliminary_scores->count())
    <div class="modal" id="preliminaryScoresModal">
      <div class="modal-background" onclick="$('#preliminaryScoresModal').removeClass('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Online Competition Scores</p>
          <button class="delete" aria-label="close"
                  onclick="$('#preliminaryScoresModal').removeClass('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <table class="table is-bordered is-fullwidth is-striped is-hoverable">
            <thead>
            <th>Criteria</th>
            <th>Score</th>
            </thead>
            <tbody>
            <tr>
              <th>Target Market</th>
              <td>{{ number_format($preliminary_scores->avg('target_market'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Solution</th>
              <td>{{ number_format($preliminary_scores->avg('solution'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Competitive Advantage</th>
              <td>{{ number_format($preliminary_scores->avg('competitive_advantage'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Team, Board, Advisers</th>
              <td>{{ number_format($preliminary_scores->avg('team_board_adviser'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Financial</th>
              <td>{{ number_format($preliminary_scores->avg('financial'), 1) + 0 }}</td>
            </tr>
            <tr>
              <th>Exit Opportunity</th>
              <th>{{ number_format($preliminary_scores->avg('exit_opportunity'), 1) + 0 }}</th>
            </tr>
            <tr>
              <th>Strategic Value</th>
              <th>{{ number_format($preliminary_scores->avg('strategic_value'), 1) + 0 }}</th>
            </tr>
            <tr>
              <th>Spoke Clearly</th>
              <th>{{ number_format($preliminary_scores->avg('spoke_clearly'), 1) + 0 }}</th>
            </tr>
            <tr>
              <th>Attitude</th>
              <th>{{ number_format($preliminary_scores->avg('attitude'), 1) + 0 }}</th>
            </tr>
            <tr>
              <th>Relate to Audience</th>
              <th>{{ number_format($preliminary_scores->avg('relate_to_audience'), 1) + 0 }}</th>
            </tr>
            <tr>
              <th>Total Score</th>
              <th>{{ number_format($preliminary_scores->avg('total_score'), 1) + 0 }}</th>
            </tr>
            </tbody>
          </table>
        </section>
      </div>
    </div>
    <div class="modal" id="preliminaryCommentsModal">
      <div class="modal-background" onclick="$('#preliminaryCommentsModal').removeClass('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Online Competition Comments</p>
          <button class="delete" aria-label="close"
                  onclick="$('#preliminaryCommentsModal').removeClass('is-active')"></button>
        </header>
        <section class="modal-card-body">
          @foreach($preliminary_scores as $preliminary_score)
            <article class="message is-dark">
              <div class="message-body">
                {{ $preliminary_score->comment }}
              </div>
            </article>
          @endforeach
        </section>
      </div>
    </div>
  @endif
@endsection

@section('scripts')
  <script>
      new Vue({
          el: '#app',
          components: {SemifinalForm, FinalForm},
          data: {
              permission: {
                  role: '{{ Auth::user()->roles()->first()->name }}',
                  create: {{ Auth::user()->can('create-event') ? 'true' : 'false' }},
                  override: {{ Auth::user()->can('override-event') ? 'true' : 'false' }},
                  id: {{ Auth::user()->getKey() }}
              }
          }
      });
  </script>
@endsection
