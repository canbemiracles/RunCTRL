<template lang="html">
  <div class="col col-md-6" v-if="loaded">
    <h2 class="">Company Settings</h2>
    <div class="form_row">
        <div class="form_row_text">Time Zone</div>
        <app-select
            :options="timeZoneList"
            @changeSelection="patch('time_zone', $event)"
            dropDownClass="select-dropdown"
            :initValue="form.time_zone.id"
        ></app-select>
    </div>
    <div class="form_row">
        <div class="form_row_text">Date Format</div>
        <app-select :options="dateFormatList"
            @changeSelection="patch('date_format', $event)"
            dropDownClass="select-dropdown"
            class="text-uppercase"
            :initValue="form.date_format.id"
        ></app-select>
    </div>
    <div class="form_row">
        <div class="form_row_text">Week Starts On</div>
        <app-select :options="weekList"
            @changeSelection="patch('week_start_on', $event)"
            dropDownClass="select-dropdown"
            :initValue="form.week_start_on"
        ></app-select>
    </div>
    <div class="form_row">
        <div class="form_row_text">Default Currency</div>
        <div class="row-wrap">
            <div class="paired_row_select_input">
                <app-select  :options="currenciesList"
                    @changeSelection="patch('currency', $event)"
                    dropDownClass="select-dropdown"
                    selectClass="text-uppercase"
                    :initValue="form.currency.id"
                ></app-select>
            </div>
            <div class="imaged_div currency-icon" v-text="currencyIcon(form.currency.currency)"></div>
        </div>
    </div>
    <div class="form_row flex-nowrap">
        <div class="paired_row paired_row_step2">
            <div class="form_row_text">Overtime hourly rate %</div>
            <div class="row-wrap">
                <div class="paired_row_select_input">
                    <app-select :options="ratesList"
                        @changeSelection="patch('overtime_hourly_rate', $event.value)"
                        dropDownClass="select-dropdown rate-select"
                        :initValue="form.overtime_hourly_rate"
                    ></app-select>
                </div>
                <div class="imaged_div">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 263.285 263.285" style="enable-background:new 0 0 263.285 263.285;" xml:space="preserve" width="18px" height="23px">
                        <path d="M193.882,8.561c-7.383-3.756-16.414-0.813-20.169,6.573L62.153,234.556c-3.755,7.385-0.812,16.414,6.573,20.169   c2.178,1.107,4.499,1.632,6.786,1.632c5.466,0,10.735-2.998,13.383-8.205L200.455,28.73   C204.21,21.345,201.267,12.316,193.882,8.561z" fill="#627680" />
                        <path d="M113.778,80.818c0-31.369-25.521-56.89-56.89-56.89C25.521,23.928,0,49.449,0,80.818c0,31.368,25.521,56.889,56.889,56.889   C88.258,137.707,113.778,112.186,113.778,80.818z M56.889,107.707C42.063,107.707,30,95.644,30,80.818   c0-14.827,12.063-26.89,26.889-26.89c14.827,0,26.89,12.062,26.89,26.89C83.778,95.644,71.716,107.707,56.889,107.707z" fill="#627680" />
                        <path d="M206.396,125.58c-31.369,0-56.89,25.521-56.89,56.889c0,31.368,25.52,56.889,56.89,56.889   c31.368,0,56.889-25.52,56.889-56.889C263.285,151.1,237.765,125.58,206.396,125.58z M206.396,209.357   c-14.827,0-26.89-12.063-26.89-26.889c0-14.826,12.063-26.889,26.89-26.889c14.826,0,26.889,12.063,26.889,26.889   C233.285,197.294,221.223,209.357,206.396,209.357z" fill="#627680" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="paired_row paired_row_step2 pr_fl_r">
            <div class="form_row_text pad_right_5">Weeked rate %</div>
            <div class="row-wrap">
                <div class="paired_row_select_input">
                    <app-select :options="ratesList"
                        @changeSelection="patch('weekend_rate', $event.value)"
                        dropDownClass="select-dropdown rate-select"
                        :initValue="form.weekend_rate"
                    ></app-select>
                </div>
                <div class="imaged_div">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 263.285 263.285" style="enable-background:new 0 0 263.285 263.285;" xml:space="preserve" width="18px" height="23px">
                        <path d="M193.882,8.561c-7.383-3.756-16.414-0.813-20.169,6.573L62.153,234.556c-3.755,7.385-0.812,16.414,6.573,20.169   c2.178,1.107,4.499,1.632,6.786,1.632c5.466,0,10.735-2.998,13.383-8.205L200.455,28.73   C204.21,21.345,201.267,12.316,193.882,8.561z" fill="#627680" />
                        <path d="M113.778,80.818c0-31.369-25.521-56.89-56.89-56.89C25.521,23.928,0,49.449,0,80.818c0,31.368,25.521,56.889,56.889,56.889   C88.258,137.707,113.778,112.186,113.778,80.818z M56.889,107.707C42.063,107.707,30,95.644,30,80.818   c0-14.827,12.063-26.89,26.889-26.89c14.827,0,26.89,12.062,26.89,26.89C83.778,95.644,71.716,107.707,56.889,107.707z" fill="#627680" />
                        <path d="M206.396,125.58c-31.369,0-56.89,25.521-56.89,56.889c0,31.368,25.52,56.889,56.89,56.889   c31.368,0,56.889-25.52,56.889-56.889C263.285,151.1,237.765,125.58,206.396,125.58z M206.396,209.357   c-14.827,0-26.89-12.063-26.89-26.889c0-14.826,12.063-26.889,26.89-26.889c14.826,0,26.889,12.063,26.889,26.889   C233.285,197.294,221.223,209.357,206.396,209.357z" fill="#627680" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    loaded: false,
    form: {},
    timeZoneList: [],
    currenciesList: [],
    dateFormatList: [],
    ratesList: [{value: 10, text: 10},{value: 20, text: 20},{value: 30, text: 30},{value: 40, text: 50},{value: 50, text: 50}],
    weekList: [
        { value: 1, text: "Monday" },
        { value: 2, text: "Tuesday" },
        { value: 3, text: "Wednesday" },
        { value: 4, text: "Thursday" },
        { value: 5, text: "Friday" },
        { value: 6, text: "Saturday" },
        { value: 7, text: "Sunday" }
    ],
  }),
  methods: {
    patch(field, e) {
      if (e.value !== this.form[field].id) {
        this.$http.patch(`api/v1/companies/${this.$auth.user().company_id}`, {[field]: e.value})
        if (field === 'currency') this.form[field].currency = e.text
      }
    },
    currencyIcon(currency){
      let formatter = new Intl.NumberFormat(this.$store.getters.language, {
          style: "currency",
          currency,
          currencyDisplay: 'symbol',
          minimumFractionDigits: 0,
          maximumFractionDigits: 0
      });
      let currency_value = formatter.format(0);
      return currency_value.replace(/0/, '');;
    }
  },

  created() {
    Promise.all([
      this.$http.get(`api/v1/date_formats/`).then(res => this.dateFormatList = res.body.map(({id, date_format}) => ({value: id, text: date_format}))),
      this.$http.get(`api/v1/currencies/`).then(res => this.currenciesList = res.body.map(({id, currency}) => ({value: id, text: currency}))),
      this.$http.get(`api/v1/time_zones/`).then(res => this.timeZoneList = res.body.map(({id, text}) => ({value: id, text: text}))),
      this.$http.get(`api/v1/companies/${this.$auth.user().company_id}`).then(res => this.form = res.body)
    ]).then(() => this.loaded = true)
  },
  components: {
    appSelect: require("../../Common/Select")
  }
}
</script>
<style lang="scss" src="../../Registration/style.scss" scoped></style>
<style lang="scss" src="./style.scss" scoped></style>
