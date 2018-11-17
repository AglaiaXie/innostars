
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
window.Vue = Vue;

window.bus = new Vue({});

import Buefy from 'buefy';
Vue.use(Buefy, {
    defaultIconPack: 'fa',
});

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

import InvestorAdmin from './components/InvestorAdmin';
window.InvestorAdmin = InvestorAdmin;

import PartnerAdmin from './components/PartnerAdmin';
window.PartnerAdmin = PartnerAdmin;

import JudgeAdmin from './components/JudgeAdmin';
window.JudgeAdmin = JudgeAdmin;

import ParticipantAdmin from './components/ParticipantAdmin';
window.ParticipantAdmin = ParticipantAdmin;

import EventAdmin from './components/EventAdmin';
window.EventAdmin = EventAdmin;

import ScheduleAdmin from './components/ScheduleAdmin';
window.ScheduleAdmin = ScheduleAdmin;

import MessageCreate from './components/MessageCreate';
window.MessageCreate = MessageCreate;

import SemifinalForm from './components/SemifinalForm';
window.SemifinalForm = SemifinalForm;

import FinalForm from './components/FinalForm';
window.FinalForm = FinalForm;

import CompetitionAdmin from './components/CompetitionAdmin';
window.CompetitionAdmin = CompetitionAdmin;

import MessageAdmin from './components/MessageAdmin';
window.MessageAdmin = MessageAdmin;

import ScheduleList from './components/ScheduleList';
window.ScheduleList = ScheduleList;
