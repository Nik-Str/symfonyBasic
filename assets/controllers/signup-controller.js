import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['email', 'password'];

  async greet() {
    console.log(this.emailTarget.value, this.passwordTarget.value);

    try {
      const response = await fetch(`${process.env.BASE_URL}/api/signup`, {
        method: 'POST',
        body: JSON.stringify({ email: this.emailTarget.value, password: this.passwordTarget.value }),
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const data = await response.json();
      console.log(data);
    } catch (err) {
      console.log(err);
    }

    this.emailTarget.value = '';
    this.passwordTarget.value = '';
  }
}
