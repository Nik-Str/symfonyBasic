import { Controller } from '@hotwired/stimulus';
import { Toast } from 'bootstrap';

export default class extends Controller {
  static targets = ['email', 'password', 'emailFeedback', 'toast', 'toastBody'];

  async auth() {
    try {
      const response = await fetch(`${process.env.BASE_URL}/api/signup`, {
        method: 'POST',
        body: JSON.stringify({ email: this.emailTarget.value, password: this.passwordTarget.value }),
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const data = await response.json();
      // User was created
      if (response.status === 201) {
        this.emailTarget.value = '';
        this.passwordTarget.value = '';
        window.location.href = `${process.env.BASE_URL}/products/male`;
      }
      // Email already registrered
      else if (response.status === 409) {
        this.emailFeedbackTarget.innerHTML = data.error;
        this.emailFeedbackTarget.classList.remove('d-none');
        this.emailTarget.classList.add('is-invalid');
      }
      // Unknown error
      else {
        this.displayError(data.error);
      }
    } catch (err) {
      console.error(err);
      this.displayError(err.message);
    }
  }

  reset() {
    this.emailFeedbackTarget.classList.add('d-none');
    this.emailFeedbackTarget.innerHTML = '';
    this.emailTarget.classList.remove('is-invalid');
  }

  displayError(msg) {
    this.toastBodyTarget.innerHTML = msg;
    const toast = new Toast(this.toastTarget);
    toast.show();
  }
}
