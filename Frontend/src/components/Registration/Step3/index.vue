<template>
    <div class="steps-container padd_top_steps">
        <div class="steps">
            <div class="step" v-if="isStepsHeader">
                <div class="round_57 step1_2_bor_col">
                    <div class="round_41">
                        <svg width="19" height="14" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#0ecdee">
                            <path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" />
                        </svg>
                    </div>
                    <div class="step_span step_text_unchecked">Sign Up</div>
                </div>

                <div class="spacer"></div>

                <router-link class="round_57 step1_2_bor_col"  :to="{name: 'Registration', params: {step: 'step-2'}}">
                    <div class="round_41">
                        <svg width="19" height="14" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#0ecdee">
                            <path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" />
                        </svg>
                    </div>
                    <div class="step_span step_text_checked">Company Info</div>
                </router-link>

                <div class="spacer"></div>

                <div class="round_70 step1_2_bor_col">
                    <div class="round_50">3</div>
                    <div class="step_span step_text_unchecked step_marg_left_5 r70_bot">Company Settings</div>
                </div>

            </div>
        </div>

        <div class="step2_title marg_bot_40">Company Settings</div>
        <transition name="fade">
            <div class="reg_form" v-if="dataReady">
                <figure>
                    <form action="" method="post">
                        <div class="form_row">
                            <div class="form_row_text">Time Zone</div>
                            <app-select 
                                :options="timeZoneList"
                                @changeSelection="changeTimeZone"
                                dropDownClass="select-dropdown"
                                :initValue="form.time_zone.id"
                            ></app-select>
                        </div>

                        <div class="form_row">
                            <div class="form_row_text">Date Format</div>
                            <app-select :options="dateFormatList"
                                @changeSelection="changeDateFormat"
                                dropDownClass="select-dropdown"
                                :initValue="form.date_format"
                            ></app-select>
                        </div>
                        <div class="form_row">
                            <div class="form_row_text">Time Format</div>
                            <app-select :options="timeFormatList"
                                @changeSelection="changeTimeFormat"
                                dropDownClass="select-dropdown"
                                :initValue="form.time_format"
                            ></app-select>
                        </div>
                        <div class="form_row">
                            <div class="form_row_text">Week Starts On</div>
                            <app-select :options="weekList"
                                @changeSelection="changeWeekStart"
                                dropDownClass="select-dropdown"
                                :initValue="form.week_start_on"
                            ></app-select>
                        </div>

                        <div class="form_row">
                            <div class="form_row_text">Default Currency</div>
                            <div class="row-wrap">
                                <div class="paired_row_select_input">
                                    <app-select  :options="currenciesList"
                                        @changeSelection="changeCurrency"
                                        dropDownClass="select-dropdown"
                                        selectClass="text-uppercase"
                                        :initValue="form.currency"
                                    ></app-select>
                                </div>
                                <div class="imaged_div currency-icon" v-text="currencyIcon"></div>
                            </div>
                        </div>
                        <div class="form_row flex-nowrap">
                            <div class="paired_row paired_row_step2">
                                <div class="form_row_text">Overtime hourly rate %</div>
                                <div class="row-wrap">
                                    <div class="paired_row_select_input">
                                        <input type="text" v-model="form.overtime_hourly_rate"
                                        :class="{'field-error': validation.hasError('form.overtime_hourly_rate')}" class="form_input" required>                               
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
                                        <input type="text" v-model="form.weekend_rate"
                                        :class="{'field-error': validation.hasError('form.weekend_rate')}" class="form_input" required>    
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
                        <div class="d-flex">
                            <div class="paired_row_step2 error-message">{{validation.firstError('form.overtime_hourly_rate')}}</div>
                            <div class="paired_row_step2 error-message">{{validation.firstError('form.weekend_rate')}}</div>
                        </div>
                         <div class="form_row marg_bot_35">
                            <div class="form_row_text">Weekend days</div>
                            <div class="row-wrap">
                                <div class="paired_row_select_input">
                                    <week-days-list
                                    :weekList="weekDays"
                                    @selectWeekDay = "selectWeekDay"
                                    @removeWeekDay = "removeWeekDay"
                                    ></week-days-list>
                                </div>
                            </div>
                        </div>
                    </form>
                </figure>
                <button type="submit" class="submit-btn marg_bot_last" :class="{disabled: submit}" @click.prevent="sendData">
                    {{ $router.currentRoute.name == 'Registration' ? 'Continue to branch creation' : 'Edit' }}
                </button>
            </div>
        </transition>
        <preloader :show="showPreloader"></preloader>
    </div>
</template>
<script>
import { Validator } from "simple-vue-validator";
import { mapGetters, mapMutations, mapActions, mapState } from "vuex";
import {getCurrencyValue} from '../../Common/utils.js'
export default {
    mixins: [require("simple-vue-validator").mixin],
    props:{
        isStepsHeader:{
            type: Boolean,
            default: true
        },
    },
    validators: {
        "form.weekend_rate"(value) {
            return Validator.value(value).required().float();
        },
        "form.overtime_hourly_rate"(value) {
            return Validator.value(value).required().float();
        }
    },
    data: function() {
        return {
        form: {
            week_start_on: 1,
            overtime_hourly_rate: 20,
            weekend_rate: 30,
            time_zone: {},
            currency: 1,
            date_format: 1,
            time_format: 1,
            weekends: [],
        },
        submit: false,
        dataReady: false,
        company_id: null,
        timeZoneList: [],
        currenciesList: [],
        dateFormatList: [],
        timeFormatList: [],
        weekList: [
            { value: 1, text: "Monday" },
            { value: 2, text: "Tuesday" },
            { value: 3, text: "Wednesday" },
            { value: 4, text: "Thursday" },
            { value: 5, text: "Friday" },
            { value: 6, text: "Saturday" },
            { value: 7, text: "Sunday" }
        ],
        weekDays: [],
        editMode: false,
        };
    },
    watch:{
        'form.week_start_on'(){
            this.initWeekGround();
        }
    },
    created() {
        this.setPreloaderState(true);
        let that = this;
        this.step3()
        .then(response => {  
            that.registr_step == 3 ? that.editMode = false : that.editMode = true;
            this.getCompanyData().then((companyData)=>{
                that.form.week_start_on = companyData.geographical_area.country.workday_start;
                if(that.editMode){
                    that.form.time_zone = companyData.time_zone;
                    that.form.currency = companyData.currency.id;
                    that.form.date_format = companyData.date_format ? companyData.date_format.id : companyData.geographical_area.country.date_format.id;
                    that.form.time_format = companyData.time_format ? companyData.time_format.id : 1;
                    that.form.overtime_hourly_rate = companyData.overtime_hourly_rate;
                    that.form.weekend_rate = companyData.weekend_rate;
                    that.form.weekends = companyData.weekends.map(day => day.id);
                    that.form.week_start_on = companyData.week_start_on;
                }else{
                    that.form.currency = companyData.geographical_area.country.currency.id;
                    that.form.date_format =companyData.geographical_area.country.date_format.id; 
                }
                this.initWeekGround(); 
            });
            
            //Подготавливаем списки для селектов
            $.each(that.time_zones, function(ind, item) {
                that.timeZoneList.push({ value: item.id, text: item.text });
            });
            $.each(that.currencies, function(ind, item) {
                that.currenciesList.push({ value: item.id, text: item.currency });
            });
            $.each(that.date_formats, function(ind, item) {
                let formatStr = item.date_format.toUpperCase();
                let formatExampleStr = moment().format(formatStr);
                let formatedDate = `(${formatExampleStr})`;
                that.dateFormatList.push({ value: item.id, text: formatStr, label: formatedDate });
            });
            $.each(that.time_formats, function(ind, item) {
                let formatExampleStr = moment().format(item.time_format);
                let formatedTime = `(${formatExampleStr})`;
                that.timeFormatList.push({ value: item.id, text: item.time_format, label: formatedTime });
            });
        })
        .then(response => {
                let timeZone;
                if(!that.editMode){
                    //инициализация таймзоны
                    timeZone = JSON.parse(localStorage.getItem('timeZone'));
                    $.each(that.time_zones, function(ind, item) {
                        item.utc.forEach(elem=>{
                            if(elem.value == timeZone){
                                that.form.time_zone = item;
                            }
                        })
                    });
                }
                that.setPreloaderState(false);
                that.dataReady = true;  
        }); 
    },
    computed: {
        ...mapGetters(["time_zones", "date_formats", "time_formats","currencies", 'company', 'registr_step']),
        ...mapState({
            showPreloader: state => state.calendar.showPreloader,
        }),
        currencyIcon(){
            let currency;
            this.currencies.forEach(el=>{
                if(el.id == this.form.currency){
                    currency = el.currency;
                }
            }) 
            let formatter = new Intl.NumberFormat(this.$store.getters.language, {
                style: "currency",
                currency,
                currencyDisplay: 'symbol',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            let currency_value = formatter.format(0);
            let symbol = currency_value.replace(/0/, '');
            return symbol;

        }
    },
    components: {
        appHeader: require("../../Header"),
        appFooter: require("../../Footer"),
        appSelect: require("../../Common/Select"),
        weekDaysList: require('./WeekDaysList'),
        preloader: require('../../Common/Preloader')
    },
    methods: {
        ...mapActions(["step3", 'getCompanyData']),
        ...mapMutations(['setStep', 'setCompanyData', 'setPreloaderState']),
        sendData: _.debounce(function(){
            this.submit=true;
            let formData = this.form;
            this.company_id = this.$auth.user().company_id; 
            this.$http({
                url: "api/v1/companies/" + this.company_id,
                body: formData,
                method: "PATCH",
                emulateJSON: false
            }).then(response => {
                    this.setCompanyData(response.body);
                    this.setStep(null);
                    this.submit=false;  
            }).then(response => {
                if(this.$router.currentRoute.name == 'Registration'){
                    this.$router.push({ name: 'branchFlow' });
                }
            });
        }, 250),
        changeTimeZone(option) {
            this.form.time_zone = option.value;
        },
        changeCurrency(option) {
            this.form.currency = option.value;
        },
        changeDateFormat(option) {
            this.form.date_format = option.value;
        },
        changeTimeFormat(option) {
            this.form.time_format = option.value;
        },
        changeWeekStart(option) {
            this.form.week_start_on = option.value;
        },
        changeHourlyRate(option) {
            this.form.overtime_hourly_rate = option.value;
        },
        changeWeekendRate(option) {
            this.form.weekend_rate = option.value;
        },
        initWeekGround() {
            if(!this.editMode){
                this.form.weekends = [];
            }
            this.weekDays = [];
            let weekList = [
                { name: "MON", id: 1 },
                { name: "TUE", id: 2 },
                { name: "WED", id: 3 },
                { name: "THU", id: 4 },
                { name: "FRI", id: 5 },
                { name: "SAT", id: 6 },
                { name: "SUN", id: 7 }
            ];
            let start = this.form.week_start_on - 1;
            for (let i = 0; i < 7; i++) {
                if (this.form.weekends.length != 0) {
                    for (let j = 0; j < this.form.weekends.length; j++) {
                        if (start > 6) {start = 0;}
                        if(this.editMode){
                            if (weekList[i].id == this.form.weekends[j]) {
                                weekList[i].selected = true;
                            }
                        }else{
                            if(i == 5 || i == 6){
                                // init last two days of week as selected
                                weekList[i].selected = true;
                                this.form.weekends.push(weekList[i].id);
                            }
                        }
                    }
                }
                this.weekDays.push(weekList[start]);
                start++;    
            }
        },
        selectWeekDay(id){
            this.form.weekends.push(id);
        },
        removeWeekDay(id){
           this.form.weekends = this.form.weekends.filter((day)=>{
               return id != day;
           }); 
        }
    }
};
</script>

<style lang="scss" src="../style.scss" scoped></style>
<style lang="scss">
.rate-select {
  height: 200px;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
.select-dropdown{
  max-height: 300px;
}
</style>


