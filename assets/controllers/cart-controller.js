import { Controller } from '@hotwired/stimulus';

// I detta exemple skulle det varit lättar att updatera DOM rakt av med JS. Men vi köra live comp för att testa.
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

  async decrement({ params }) {
    try {
      const jsonResponse = await fetch(`${process.env.BASE_URL}/api/updatecart`, {
        method: 'POST',
        body: JSON.stringify({ id: params.id, type: 'dec' }),
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const response = await jsonResponse.json();
      const hasLength = response.data.length;
      document.querySelector('#cartCounter').innerHTML = hasLength ? hasLength : null;
    } catch (err) {
      console.log(err);
    }
  }
}
