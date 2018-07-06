

import {mount , createLocalVue } from '@vue/test-utils';
import ShiftItem from '../BranchCreation/BranchFlow/BranchShifts/ShiftItem/index.vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue'
import Vuex from 'vuex';
import jest from 'jest-mock';
const vueAuth = require('@websanova/vue-auth');


const localVue = createLocalVue();
localVue.use(vueAuth);
localVue.use(VueRouter);
localVue.use(Vuex);
localVue.use(BootstrapVue);



describe ('ShiftItem', ()=>{
    it('branch shifts test', () =>{
        const wrapper = mount(ShiftItem, {
            localVue
        })
        expect(wrapper.isVueInstance()).toBeTruthy();
    })
})