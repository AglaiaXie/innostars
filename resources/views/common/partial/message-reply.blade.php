<div class="modal" id="thread_modal">
	<div class="modal-background" onclick="closeThreadModal()"></div>
	<div class="modal-content">
		<div class="box">
			<div id="messages">
			</div>
			<hr>
			<form method="POST" action="" id="replyForm">
				{{ csrf_field() }}
				{{ method_field('PUT') }}
				<article class="media">
					<figure class="media-left">
						<p class="image is-64x64">
							<img src="https://bulma.io/images/placeholders/128x128.png">
						</p>
					</figure>
					<div class="media-content">
						<input type="hidden" id="uid" name="uid">
						<div class="field">
							<p class="control">
                  <textarea class="textarea" id="message" name="message" placeholder="Write your message..."
                            required></textarea>
							</p>
						</div>
						<nav class="level">
							<div class="level-left">
								<div class="level-item">
									<button type="submit" class="button is-info">Send</button>
								</div>
							</div>
						</nav>
					</div>
				</article>
			</form>
		</div>
		<button class="modal-close is-large" aria-label="close" onclick="closeThreadModal()"></button>
	</div>
</div>
<div hidden>
	<article class="media" id="template">
		<figure class="media-left">
			<p class="image is-64x64">
				<img src="https://bulma.io/images/placeholders/128x128.png">
			</p>
		</figure>
		<div class="media-content">
			<div class="content">
				<p>
					<strong data-name></strong>
					<small data-date></small>
				</p>
				<p data-message>
				</p>
			</div>
		</div>
	</article>
</div>