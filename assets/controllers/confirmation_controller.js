import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["popup"]

    connect() {
        this.popupTarget.classList.add('hidden');
    }

    show(event) {
        event.preventDefault();
        this.popupTarget.classList.remove('hidden');

        setTimeout(() => {
            event.target.submit();
        }, 1000);
    }

    close() {
        this.popupTarget.classList.add('hidden');
    }
}
