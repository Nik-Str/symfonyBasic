import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  async increment({ params }) {
    try {
      await fetch(`${process.env.BASE_URL}/api/updatecart`, {
        method: 'POST',
        body: JSON.stringify({ id: params.id, type: 'inc' }),
        headers: {
          'Content-Type': 'application/json',
        },
      });
    } catch (err) {
      console.log(err);
    }
  }
}
