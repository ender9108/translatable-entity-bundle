import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = []
    static values = {
        availableLocales: Array
    }

    connect() {
        this.element.querySelectorAll('label').forEach(label => {
            const text = label.innerText;
            const currentLocale = label.closest('.form-element').dataset.locale;

            label.innerHTML = `
                <span>${text} (${currentLocale})</span>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-globe"></i>
                    </button>
                    <ul class="dropdown-menu"></ul>
                </div>
            `;

            label.classList.add('m-0', 'p-0');

            const dropdown = label.querySelector('ul.dropdown-menu');

            this.availableLocalesValue.forEach(locale => {
                const li = document.createElement('li');
                li.dataset.locale = locale;
                const link = document.createElement('a');
                link.classList.add('dropdown-item')
                link.href = '#';
                link.innerText = locale.capitalize();

                if (locale === currentLocale) {
                    link.classList.add('active');
                }

                link.addEventListener('click', event => {
                    event.stopPropagation();
                    event.preventDefault();

                    this.switchField(locale);
                });

                li.append(link);
                dropdown.append(li);
            });
        });
    }

    switchField(locale) {
        this.element.querySelectorAll('.form-element').forEach(element => {
            if (element.dataset.locale === locale) {
                element.classList.remove('d-none');
            } else {
                element.classList.add('d-none');
            }
        });
    }
}