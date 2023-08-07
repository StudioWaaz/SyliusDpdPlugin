const tnt = {
    cityInputs: [],
    citySelects: [],
    countryElts: [],
    postcodeInputs: [],
    url: null,
    init() {
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-tnt-url]').forEach((cityInput, key) => {
                this.cityInputs[key] = cityInput;
                this.url = cityInput.dataset.tntUrl;
                this.generateSelect(key);
                const postcodeSelector = cityInput.name.replace(/\[city\]/, '[postcode]');
                const postcodeInput = document.querySelector(`[name='${postcodeSelector}']`);
                this.postcodeInputs[key] = postcodeInput;
                const countrySelector = cityInput.name.replace(/\[city\]/, '[countryCode]');
                const countryInput = document.querySelector(`[name='${countrySelector}']`);
                this.countryElts[key] = countryInput;
                this.cityFieldConfiguration(key);
                this.attachPostcodeListener(key);
                this.attachCountryListener(key);
                this.verifyDataAndCreateOptions(key);
            })
        })
    },
    async fetchCities(key) {
        if (this.url) {
            const postcode = this.postcodeInputs[key].value;
            const response = await fetch(this.url.replace('xxxxx', postcode));
            const data = await response.json();
            return data;
        }
    },
    async buildSelectOptions(key) {
        const select = this.citySelects[key];
        const cities = await this.fetchCities(key);
        select.innerHTML = '';
        select.disabled = true;
        if (cities.length > 0) {
            select.innerHTML += `<option value=""></option>`
            const input = this.cityInputs[key];
            cities.forEach((cityName) => {
                if (input.value === cityName) {
                    select.innerHTML += `<option value="${cityName}" selected>${cityName}</option>`
                } else {
                    select.innerHTML += `<option value="${cityName}">${cityName}</option>`
                }
            });
        }
        select.disabled = false;
    },
    cityFieldConfiguration(key) {
        const country = this.countryElts[key];
        const cityInput = this.cityInputs[key];
        const citySelect = this.citySelects[key];
        
        if (country.value === 'FR') {
            cityInput.style.display = 'none';
            citySelect.style.display = 'block';
            citySelect.disabled = true;
        } else {
            cityInput.style.display = 'block';
            citySelect.style.display = 'none';
        }

        this.verifyDataAndCreateOptions(key);
        
    },
    generateSelect(key) {
        const cityInput = this.cityInputs[key];
        const select = document.createElement('select');
        select.id = `city-select-${key}`;
        cityInput.after(select);
        this.citySelects[key] = select;
        select.addEventListener('change', () => {
            cityInput.value = select.value;
        })
        
        select.className = cityInput.dataset.tntSelectClasses ?? '';
    },
    attachPostcodeListener(key) {
        const postcodeInput = this.postcodeInputs[key];
        postcodeInput.addEventListener('input', () => {
            this.verifyDataAndCreateOptions(key);
        });
    },
    attachCountryListener(key) {
        const countryInput = this.countryElts[key];
        countryInput.addEventListener('change',() => {
            this.cityFieldConfiguration(key);
        })
    },
    verifyDataAndCreateOptions(key) {
        const postcodeInput = this.postcodeInputs[key];
        const country = this.countryElts[key].value;
        if (country === 'FR' && postcodeInput.value.length >= 5) {
            this.buildSelectOptions(key);
        }
    }
}

tnt.init();