import { Controller } from '@hotwired/stimulus';
import { Toast } from 'bootstrap';

export default class extends Controller {
  static targets = ['email', 'password', 'emailFeedback', 'passwordFeedback', 'toast', 'toastBody', 'loading', 'btn'];

  async auth() {
    try {
      this.btnTarget.disabled = true;
      this.loadingTarget.classList.remove('d-none');

      const response = await fetch(`${process.env.BASE_URL}/api/login`, {
        method: 'POST',
        body: JSON.stringify({ email: this.emailTarget.value, password: this.passwordTarget.value }),
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const data = await response.json();
      // User was logged in
      if (response.status === 201) {
        this.emailTarget.value = '';
        this.passwordTarget.value = '';
        window.location.href = `${process.env.BASE_URL}/products/male`;
      }
      // Invalid Email or Password
      else if (response.status === 409) {
        this.invalidAuth(this.emailFeedbackTarget, this.emailTarget, data);
        this.invalidAuth(this.passwordFeedbackTarget, this.passwordTarget, data);
      }
      // Unknown error
      else {
        this.displayError(data.error);
      }
    } catch (err) {
      console.error(err);
      this.displayError(err.message);
    } finally {
      this.btnTarget.disabled = false;
      this.loadingTarget.classList.add('d-none');
    }
  }

  invalidAuth(feedback, input, data) {
    feedback.innerHTML = data.error;
    feedback.classList.remove('d-none');
    input.classList.add('is-invalid');
  }

  reset() {
    const res = (feedback, target) => {
      feedback.classList.add('d-none');
      feedback.innerHTML = '';
      target.classList.remove('is-invalid');
    };
    res(this.emailFeedbackTarget, this.emailTarget);
    res(this.passwordFeedbackTarget, this.passwordTarget);
  }

  displayError(msg) {
    this.toastBodyTarget.innerHTML = msg;
    const toast = new Toast(this.toastTarget);
    toast.show();
  }
}
