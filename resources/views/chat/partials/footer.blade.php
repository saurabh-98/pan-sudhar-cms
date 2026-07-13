<div class="chat-footer">

    <form
        id="chat-form"
        method="POST"
        enctype="multipart/form-data"
        autocomplete="off"
    >

        @csrf

        <input
            type="hidden"
            id="conversation_id"
            name="conversation_id"
            value="{{ $conversation->conversation_id }}"
        >

        <!-- Attachment Preview -->
        <div
            id="attachment-preview"
            class="attachment-preview d-none"
        >

            <div class="attachment-card">

                <div class="d-flex align-items-center">

                    <i class="fas fa-paperclip me-2"></i>

                    <span id="attachment-name"></span>

                </div>

                <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    id="remove-attachment"
                >

                    <i class="fas fa-times"></i>

                </button>

            </div>

        </div>

        <!-- Toolbar -->
        <div class="chat-toolbar">

            <!-- Emoji -->
            <button
                type="button"
                class="btn btn-light"
                id="emoji-button"
                title="Emoji"
            >

                😊

            </button>

            <!-- Attachment -->
            <button
                type="button"
                class="btn btn-light"
                id="attachment-button"
                title="Attachment"
            >

                <i class="fas fa-paperclip"></i>

            </button>

            <input
                type="file"
                id="attachment"
                name="attachment"
                accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip"
                hidden
            >

        </div>

        <!-- Message Box -->
        <div class="message-box">

            <textarea
                id="message"
                name="message"
                rows="1"
                maxlength="5000"
                placeholder="Type your message..."
                autocomplete="off"
            ></textarea>

        </div>

        <!-- Footer -->
        <div class="chat-footer-bottom">

            <div class="typing-status">

                <span id="typing-indicator"></span>

            </div>

            <div class="footer-actions">

                <small
                    id="character-count"
                    class="text-muted"
                >
                    0 / 5000
                </small>

                <button
                    type="submit"
                    id="send-message"
                    class="btn btn-success"
                >

                    <span
                        id="send-spinner"
                        class="spinner-border spinner-border-sm d-none me-2"
                    ></span>

                    <i class="fas fa-paper-plane"></i>

                    <span>

                        Send

                    </span>

                </button>

            </div>

        </div>

    </form>

</div>