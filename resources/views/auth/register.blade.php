@extends('laravel-bulma-starter::layouts.bulma')

@section('content')
  <div class="columns">
    <div class="column is-half is-offset-one-quarter">
      <div class="card ">
        <header class="card-header">
          <p class="card-header-title">
            InnoSTARS Register
          </p>
        </header>
        <div class="card-content">
          <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
            {{ csrf_field() }}
            <div class="content">
              <div class="field">
                <label class="label">First Name 名</label>
                <div class="control">
                  <input name="first_name"
                         class="input{{ $errors->has('first_name') ? ' is-danger' : '' }}" type="text"
                         value="{{ old('first_name') }}" required autofocus>
                </div>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'first_name', 'type' => 'horizontal'])

              <div class="field">
                <label class="label">Last Name 姓</label>
                <div class="control is-fullwidth">
                  <input name="last_name"
                         class="input{{ $errors->has('last_name') ? ' is-danger' : '' }}" type="text"
                         value="{{ old('last_name') }}" required>
                </div>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'last_name', 'type' => 'horizontal'])

              <div class="field">
                <label class="label">E-mail 邮箱</label>
                <div class="control is-fullwidth">
                  <input name="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}"
                         type="text"
                         value="{{ old('email') }}" required>
                </div>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'email', 'type' => 'horizontal'])

              <div class="field">
                <label class="label">Password 密码</label>
                <div class="control is-fullwidth">
                  <input name="password"
                         class="input{{ $errors->has('password') ? ' is-danger' : '' }}"
                         type="password"
                         value="{{ old('password') }}" required>
                </div>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'password', 'type' => 'horizontal'])

              <div class="field">
                <label class="label">Confirm password 确认密码</label>
                <div class="control is-fullwidth">
                  <input name="password_confirmation"
                         class="input{{ $errors->has('password-confirm') ? ' is-danger' : '' }}"
                         type="password"
                         value="{{ old('password-confirm') }}" required>
                </div>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'password-confirm', 'type' => 'horizontal'])

              <hr>

              <div class="field">
                <label class="label">Registration Type 账号类型</label>
                <div class="control">
                  <label class="radio">
                    <input type="radio" name="user_type" {{ (old('user_type') === 'participant' ? 'checked' : Request::get('type', 'contestant') === 'contestant' ? 'checked' : '')}} value="participant">
                    Contestant 参赛公司
                  </label>
                  <label class="radio">
                    <input type="radio" name="user_type" {{ old('user_type') === 'judge' ? 'checked' : Request::get('type', 'contestant') === 'judge' ? 'checked' : ''}} value="judge">
                    Judge 评委
                  </label>
                  <label class="radio">
                    <input type="radio" name="user_type" {{ old('user_type') === 'investor' ? 'checked' : Request::get('type', 'contestant') === 'investor' ? 'checked' : ''}} value="investor">
                    Investor 投资人
                  </label>
                  <label class="radio">
                    <input type="radio" name="user_type" {{ old('user_type') === 'partner' ? 'checked' : Request::get('type', 'contestant') === 'partner' ? 'checked' : ''}} value="partner">
                    Partner 合作伙伴
                  </label>
                </div>
              </div>

              <div class="field">
                <label class="checkbox">
                  <input type="checkbox" name="agree_term">
                  I agree to the <a href="#" onclick="$('#termModal').addClass('is-active')">terms and conditions</a> <br>
                  同意 <a href="#" onclick="$('#termModal').addClass('is-active')">用户协议</a>
                </label>
              </div>
              @include('laravel-bulma-starter::components.forms-errors', ['field' => 'agree_term', 'type' => 'horizontal'])

              <div class="field">
                <div class="control-label">
                  <!-- spacer -->
                </div>
                <div class="control is-fullwidth">
                  <button class="button is-primary">Register 注册</button>
                </div>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="termModal">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Terms and Conditions</p>
      </header>
      <section class="modal-card-body">
        <div class="content">
          <h1>InnoSTARS COMPETITION 2018 OFFICIAL RULES</h1>
          <h2>1. Introduction</h2>
          <p>a) The goal of the InnoSTARS Competition is to provide U.S.-based innovative technology companies
            with resources to enter the Chinese market. The Competition is hosted and promoted by the US-China
            Innovation Alliance (UCIA) and the China Science and Technology Exchange Center (CSTEC). Supported by the
            Ministry of Science and Technology of People’s Republic of China (MOST) (collectively, “Hosts”), the
            Competition aims to provide U.S.-based innovators and companies a better understanding of the opportunities
            available to companies interested in doing business in China as well as interfacing directly with qualified
            Chinese partners and investors.</p>
          <p>b) The Competition can be accessed via the InnoSTARS Competition website.</p>
          <p>c) The Competition Period is from January 2018 to November 2018.</p>
          <p>d) By registering, you confirm that you have read and agree to all of the terms and conditions set forth in
            the Official Rules and that you accept any and all of the Hosts’ decisions regarding the Competition as
            final and binding in all respects. This does not affect your statutory rights.</p>
          <p>e) “Competition Materials” means the Submissions (as defined in Section 7 below) and other materials
            generated by an Applicant as part of their involvement in the Competition.</p>
          <p>f) The Hosts may pass on any Competition Materials submitted by Applicants via the Competition website to
            other third parties involved in the operation, evaluation or judging of the Competition including, for the
            avoidance of doubt, for marketing and communications purposes.</p>
          <p>g) All Competition Materials must be in the English language.</p>
          <p>h) The Competition is not a formal part of the UCIA recruitment process and participation in the
            Competition will not influence any subsequent applications made by an Applicant for a job, internship or
            other training scheme with the Hosts or any of its affiliates.</p>
          <p>i) An “Individual Applicant” means a person who enters the Competition as a sole individual whereas a “Team
            Applicant” means a person who enters the Competition as part of a team. Individual Applicants and Team
            Applicants are together referred to as “Applicants”.</p>
          <h2>2. Restrictions on the Export of Technical Information</h2>
          <p>a) The U.S. government regulates the export of certain technical data and information from the U.S. along
            with the release of technical data and information to foreign nationals located in the U.S. Applicants
            submitting Submissions containing technical details of a technology must ensure that the subject technology
            is not controlled under U.S. Export Control laws. Applicants should seek independent legal advice for
            assistance in this determination.</p>
          <p>b) Rest of the world – Applicants must ensure that their Submissions comply with their jurisdiction’s
            equivalent or similar export regulations. Applicants should seek independent legal advice for assistance in
            this determination.</p>
          <p>c) By entering Submissions, Applicants certify that any technical information contained in their
            Submissions is not restricted for export to the People’s Republic of China.</p>
          <h2>3. Eligibility</h2>
          <p>a) The Applicant must be a representative of a registered U.S. company with innovative technologies or
            products that have been or can be commercialized.</p>
          <p>b) Application must feature a technology that falls under one or more of the focus industries.</p>
          <p>c) Products, technologies, patents, and control of IP or exclusive licensing rights must be owned by the
            Applicant, or his/her company, and must not have any potential or outstanding IP ownership disputes.</p>
          <p>d) The Applicant must have an interest to seek Chinese investment and/or explore the Chinese market.</p>
          <p>e) The Applicant is committed to continuing the development of products and technologies with the intent to
            further commercialize these assets in the foreseeable future.</p>
          <p>d) Revenue generation and profitability are preferred but not required.</p>
          <h2>4. Registration</h2>
          <p>a) Eligible Applicants wishing to participate in the Competition must each register online on the InnoSTARS
            Competition website regardless of whether they wish to enter the InnoSTARS Competition as an Individual
            Applicant or as a Team Applicant.</p>
          <p>b) An Eligible Applicant may only register for entry into the Competition once. An Eligible Applicant may
            only participate in the Competition either as an Individual Applicant or as a Team Applicant but not as
            both.</p>
          <p>c) The registration period for participating in the Competition is from January 2018 to May 2018
            inclusive (the “Registration Period”). Internet access is required.</p>
          <p>d) All Applicants must provide a valid email address as part of the registration process. Applicants are
            responsible for updating their e-mail address if it changes during the Competition Period.</p>
          <p>e) The Hosts reserve the right to check the validity of the registration information submitted by a
            Applicant at any stage during the Competition.</p>
          <p>f) The Hosts also reserves the right to refuse participation, or to disqualify at any time during the
            Competition, Applicants who have submitted incorrect or misleading information. Applicants who do not comply
            with these Terms and Conditions may also be disqualified from the Competition without further notice.</p>
          <p>g) Applicants will have no recourse against disqualification decisions.</p>
          <p>h) Any Applicant who does not submit an application correctly by the given deadline will not be able to
            participate in the Competition.</p>
          <p>i) For the avoidance of doubt, all times mentioned in the Competition materials are based on U.S. Central
            Time (CT).</p>
          <h2>5. Teams</h2>
          <p>a) Changes to the composition of a Team can be made at any time until the deadline via the Competition
            Website.</p>
          <p>b) Each Team must appoint a main contact person and inform the Hosts of the contact person. Emails sent by
            the Hosts to the email address provided by the team will be considered delivered to each Team’s main contact
            person.</p>
          <h2>6. Competition Requirements, Submissions, Evaluation, and Selection of the Winner</h2>
          <p>a) Each Individual Applicant/Team will be required to submit via the Competition Website the following IN
            ENGLISH:</p>
          <ul>
            <li>Company Name</li>
            <li>Company Size</li>
            <li>Year Established</li>
            <li>Website</li>
            <li>Company Location</li>
            <li>Contact Information</li>
            <li>Technology/Project Information Includes Technology/Project Description, Development Stage,
              Patents, and Revenue for 2017.
            </li>
            <li>Industries of Focus</li>
            <li>Additional information includes an Executive Summary, Technology/Project Pitch Deck, Video
              Pitch, and
              Objective of Participation in the InnoSTARS Competition.
            </li>
          </ul>
          <p>b) Judges are principals of investment fund (fund size > $50 million) and renowned experts in the related
            industries.</p>
          <p>c) Submissions will be assessed by reference to the following criteria, which shall be of equal
            importance:</p>
          <ul>
            <li>Relevance in meeting the needs of the Chinese market, including competitiveness and innovativeness.
            </li>
            <li>Readiness for the Chinese market, including scalability, sustainability, and other important market
              factors.
            </li>
          </ul>
          <p>d) Submissions may not be libellous, offensive, obscene or in violation of intellectual property rights or
            rights of privacy or publicity. The Hosts reserves the right to disqualify an Individual Applicant/Team if a
            Submission or any part thereof falls under one of the previously mentioned categories.</p>
          <h2>7. Correspondence</h2>
          <p>a) All correspondence throughout the Competition must be in the English language.</p>
          <p>b) The Competition Website will aim to cover many of the questions that Applicants and other interested
            parties may have. Any additional questions or comments concerning the Competition should be sent
            via email to Info@uschinainnovation.org.</p>
          <p>c) The Hosts makes no guarantee of an answer or a time in which an answer may be given. The Hosts reserves
            the right not to answer a question if, in the Hosts’ opinion, an answer would give an unfair advantage to
            certain Applicants.</p>
          <p>d) Applicants who have received unofficial or content sensitive information relating to the Competition
            with the intent of allowing an unfair competitive advantage, from employees, interns, apprentices and
            trainees or any party involved with the Competition must immediately inform the Hosts by email to
            Info@uschinainnovation.org.</p>
          <h2>8. Prizes</h2>
          <p>a) The Applicants chosen as the Winners of the Preliminary Stage will win sponsored trips to attend the
            area Semi-finals in China. The Trip will be chosen by the Winner from a list of Chinese cities provided by
            the Hosts. The Trip must be completed within one (1) calendar year of the prize being awarded on dates
            determined by the Trip sponsor in its sole discretion or the prize will be forfeited in its entirety. Space
            is subject to availability at the time of booking. Translation services will be provided on the trips. The
            dates of the Trips concern travel to China and from the United States. Travel dates may vary for Winners
            originating from and returning to other locations. Trips to China may be physically demanding; Winners may
            be required to confirm that they are physically prepared and have no existing health conditions that would
            limit their mobility. The actual value of the Trip may vary depending on the Chinese cities chosen, airfare
            fluctuations, and the distance between departure and destination. Winners will not receive any difference
            between actual and approximate retail value.</p>
          <p>d) The Hosts will organize and pay for round-trip economy-class air travel from any U.S. city to China,
            trip accommodation or seven days in China (up to three cities), including transportation, lodging, meals,
            and professional business matchmaking services. All expenses and incidental travel costs not expressly
            stated in the package description above include, but are not limited to (i.e., the following are the sole
            responsibility of the Preliminary Stage Winners), ground transportation, meals, incidentals, gratuities,
            passenger tariffs or duties, airline fees, surcharges, airport fees, service charges or facility charges,
            personal charges at lodging, security fees, taxes, visas, and immunizations, and insurance, or other
            expenses.</p>
          <p>e) Each Applicant will be wholly responsible for the payment of any tax, insurance, contribution, or amount
            of any kind due (if any) in respect of the award of a prize under the Competition.</p>
          <p>f) The Hosts reserve the right to award additional prizes during or at the end of the Competition.</p>
          <p>g) Prizes cannot be redeemed as cash, are non-transferable, and no substitution will be made except at the
            Hosts’ sole discretion. The Hosts reserve the right to substitute a prize for one of equal or greater value
            if the designated prize should become unavailable for any reason.</p>
          <h2>9. Intellectual Property</h2>
          <p>a) Applicants agree that all stages of the competition may be open to the public at large. Any and all of
            these stages may be broadcast to interested persons through media, which may include radio, television and
            the Internet. The Competition Materials should be considered information that could possibly enter the
            public realm, and Applicants should not assume any right of confidentiality in any data or information
            discussed, divulged, or presented in these Stages.</p>
          <p>b) Due to the nature of the competition, the Hosts will not be able to ask judges, staff, or the audience
            to agree to or sign Non Disclosure Statements. The Hosts will, however, make every effort to limit
            distribution of Competition Materials presented at the competition. The Hosts cannot guarantee that other
            individuals will not obtain access to electronic or hard copies of the Competition Materials. All
            presentations are open to the general public, and some presentations may be videotaped. Attendance by media
            personnel is expected.</p>
          <p>c) The Hosts or its affiliates may pass on any Competition Materials to their venture capital and other
            commercial partners on a non-confidential basis.</p>
          <p>d) The Applicants warrant that the contents of the Competition Materials are entirely of that Applicants’
            own creation and do not in any way infringe the intellectual property rights of any other person. If it is
            discovered that any entry is not the original creation of a Applicant and/or breaches and other person’s
            intellectual property rights, the Hosts shall be entitled to disqualify that entry and the Applicants from
            the Competition.</p>
          <p>e) The Hosts do not verify the adequacy, accuracy or completeness of Competition Materials. The Applicants
            are responsible for ensuring compliance with all applicable federal, state and other securities laws. </p>
          <p>f) The Hosts reserve the right to use any materials prepared for the InnoSTARS Competition, including the
            Competition materials, in publicizing and promoting the event. The Hosts may use these materials in any
            printed materials and any videotape or other medium that it may produce. The Hosts may record any portion of
            the Competition and utilize the recordings in any way that it sees fit.</p>
          <h2>10. Privacy</h2>
          <p>a) The Hosts collect the personal data of Applicants as stated on the online registration form on the
            Competition Website for the purposes of organizing the management of the Competition (including arranging
            receipt of and/or participating in the prizes) and communicating with Applicants in relation to the
            Competition and any related recruitment activities. Personal data provided by Applicants will only be used
            in accordance with the Privacy Policy as stated on the Competition Website. By providing personal data to
            the Hosts, Applicants are consenting to its use in accordance with the Privacy Policy.</p>
          <p>b) Applicants may be requested to take part in promotional activities relating to their involvement in the
            Competition, and the Hosts reserve the right to use the name(s) of Applicants, their photographs, and
            audio/visual recordings of them for any type of publicity. Applicants may also be required to take part in
            further reasonable promotional activities arranged by the Hosts.</p>
          <p>c) The Hosts will not keep personal data relating to Applicants for longer than is necessary. Data relating
            to Applicants will be retained by the Hosts for a reasonable period after the Competition closes in order to
            assist the Hosts in operating competitions in a consistent manner and to deal with any queries relating to
            the Competition.</p>
          <h2>11. Confidentiality</h2>
          <p>The Hosts protect the confidentiality of all contestants’ applications, technologies and products in the
            following ways:</p>
          <p>a) The Hosts must not share confidential information of contestants and submitted material with
            unauthorized personnel.</p>
          <p>b) The Hosts request that all judges must not share confidential information of contestants and submitted
            material with unauthorized personnel. However, the Hosts are not responsible for actions or verbal
            statements by the judges.</p>
          <h2>12. Claims and Disputes</h2>
          <p>a) Any Applicant or Team suspected by the Hosts of plagiarism may be investigated, and, if deemed
            appropriate by the Hosts (in its absolute discretion), disqualified from the Competition.</p>
          <p>b) Applicants should notify the Hosts of any disputes regarding the Competition within one (1) calendar
            month of the end of the Competition by email to Info@uschinainnovation.org with the word 'Dispute' included
            in the subject header.</p>
          <p>c) The Hosts’ decision and discretion on any dispute shall be final and no correspondence will be entered
            into on that matter.</p>
          <p>d) Save for the agreement between the Hosts and each Applicant for the provision of the Competition as set
            out herein, participation in the Competition shall in no event be considered or construed as giving rise to
            any contractual relations with the Hosts or any of its affiliates and, in particular, shall not give rise to
            any employment relationship.</p>
          <h2>13. Disclaimers</h2>
          <p>a) To protect the rights of Applicants, we strongly encourage all applicants to raise the awareness of
            intellectual property rights. In cases of IP violations to third parties, the Hosts reserve the rights to
            disqualify any team committing the violations. The Hosts are not responsible for any of the legal redress
            and have the rights to disqualify any applicant in case of such violations.</p>
          <p>b) Applicants are fully responsible for any legal redress of participating in the Competition including,
            but not limited to, violation of third party patents, copyrights, trademark rights, and privacy rights etc.
            The Hosts reserve the right to disqualify contestants in cases of such violations and is not responsible for
            the legal redress.</p>
          <p>c) The selection of the contestants to compete and the decision of the winners are at the sole discretion
            of the judges and the Hosts. </p>
          <p>d) The Hosts reserve the right to use any material prepared for the InnoSTARS in publicizing and promoting
            the event. The Hosts may use these materials in any printed materials or other medium that they may produce,
            including but are not limited to website, social media, and videotape. </p>
          <p>e) The rules and prizes are subject to change at the discretion of the Hosts at any time. The Hosts reserve
            the right to interpret these rules according to its own judgement.</p>
          <h2>14. General</h2>
          <p>a) The Hosts assumes no responsibility or liability for any loss arising out of or from: (i) technical
            issues, system or software failures experienced by a Applicant in submitting their registration/Submission
            or accessing the Competition Website; (ii) user errors; (iii) negligent use of the Competition Website; or
            (iv) late, lost, delayed, damaged, misdirected, incomplete or unintelligible registrations/Submissions.
            Proof of sending will not be accepted as proof of receipt.</p>
          <p>b) The Hosts try to ensure the standard of the Competition Website remains high but cannot be held
            responsible for interruptions of service. The Hosts reserves the right to suspend temporarily the operation
            of the Competition Website without notice in the case of system failure, maintenance or repair, or for any
            other reason beyond its control.</p>
          <p>c) The Hosts make no promises or warranties (either express or implied) that use of the application will be
            uninterrupted, error-free, or fit for any particular purpose.</p>
          <p>d) By accepting a prize, a Winner agrees to release and hold the Hosts and its affiliates harmless against
            any and all claims and liability arising out of the award of, including the use or misuse of any prize.
            Where the law implies warranties, which cannot be excluded, the Hosts’ liability for breach of those
            warranties is limited to resupplying the prize (or paying for the costs thereof), where this is permitted by
            law. A Winner assumes all liability for any injury or damage caused, or claimed to be caused by
            participation in this Competition or use or redemption of any prize.</p>
          <p>e) The Hosts shall not be liable to Applicants under or in connection with the Competition for any
            indirect, economic or consequential loss or for any loss of profits, loss of business, loss of contracts,
            loss of use or loss of reputation.</p>
          <p>f) The Hosts reserve the right at its sole discretion to cancel, terminate, modify or suspend the
            Competition in whole or in part at any time.
          </p>
          <p>g) The Hosts may, at its sole discretion, disqualify an Individual Applicant or a Team from participating
            further in the Competition if the Individual Applicant/Team shows a disregard for these Terms & Conditions
            or acts in any unsporting or disruptive manner.</p>
          <p>h) The Hosts reserve the right to monitor any information/materials posted on or submitted through the
            Competition Website by a Applicant. The Hosts, at its sole discretion and without prior notice, may at any
            time review, remove, or otherwise block any information/materials posted on or submitted through the
            Competition Website.</p>
          <p>i) The Competition Website offers Applicants the opportunity to join in or read from a forum and Applicants
            acknowledge that any communications posted on such forum represents the views of the individual who posted
            such communication and are not to be taken as the views of the Hosts. The Hosts accept no responsibility or
            liability for anything posted on the forum by any user of the forum and Applicants must not use the forum to
            post, upload, or otherwise transmit information or pictures that are defamatory, offensive, obscene, a
            breach of privacy or otherwise unlawful.</p>
          <p>j) The Hosts reserve the change the the rules and prizes of the Competition any time and unilaterally amend
            these Terms and Conditions from time to time.</p>
          <p>k) If the Applicant has submitted the same, or a broadly similar, idea in any other competition, details of
            the competitions in which that idea has been used must be provided to the Hosts. The Hosts reserve the right
            to disqualify any entry which is composed of substantially the same idea or concept as has been submitted by
            the relevant Applicant in any other competition.</p>
          <p>l) If any provision of these Terms and Conditions is declared by any court of competent jurisdiction to be
            invalid, illegal or unenforceable, the validity, legality, and enforceability of the remaining provisions
            contained in these Terms and Conditions will be not affected or impaired in any way.</p>
          <p>m) These Terms and Conditions shall be interpreted in accordance with and governed by the laws of the State
            of Texas and any dispute arising out of or in connection with them will be subject to the exclusive
            jurisdiction of the Texas courts.</p>
          <p>n) This competition is void where prohibited or restricted by law.</p>
          <h1>EXECUTIVE SUMMARY OF OFFICIAL RULES</h1>
          <ul>
            <li>All Competition Materials submitted are considered NON-CONFIDENTIAL.</li>
            <li>The Hosts may pass on the Competition Materials to venture capital and other partners.</li>
            <li>All information or advice provided as part of the Competition website is intended to be general in
              nature. The Hosts are not liable for any action a Applicant may take as a result of relying on such
              information or advice or for any loss or damage suffered by the Applicant as a result of taking this
              action.
            </li>
            <li>The Hosts reserve the right to use any materials prepared for the InnoSTARS Competition, including the
              Competition materials, in publicizing and promoting the event.
            </li>
            <li>The U.S. government regulates the export of certain technical data and information from the U.S. along
              with the release of technical data and information to foreign nationals located in the U.S. When
              disclosing technical details of a technology, a Applicant MUST ensure that the subject technology is not
              controlled under U.S. Export Control laws. This is the Applicant’s sole responsibility. Other countries
              may also have equivalent or similar export regulations.
            </li>
          </ul>
        </div>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" onclick="$('#termModal').removeClass('is-active')">Close</button>
      </footer>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
      document.addEventListener("DOMContentLoaded", function () {
          $('input[type=radio][name=user_type]').change(function (event) {
              if (this.checked && this.value === 'judge') {
                  $('#competition_selector').slideUp();
              } else {
                  $('#competition_selector').slideDown();
              }
          });
      });
  </script>
@endsection