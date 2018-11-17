<div class="modal" id="message_modal">
	<div class="modal-background" onclick="closeMessageModal()"></div>
	<div class="modal-content">
		<form method="POST">
			{{ csrf_field() }}
			<div class="box">
				<article class="media">
					<div class="media-content">
						<input type="hidden" id="uid" name="uid">
						<div class="field">
							<p class="control">
								<input class="input" type="text" id="subject" name="subject" placeholder="Your subject" required>
							</p>
						</div>
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
			</div>
		</form>
	</div>
	<button class="modal-close is-large" aria-label="close" onclick="closeMessageModal()"></button>
</div>