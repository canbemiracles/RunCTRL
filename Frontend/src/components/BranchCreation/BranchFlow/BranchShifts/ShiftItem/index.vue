<template>
<div class="shifts-wrap d-flex flex-column">
  <div class="shift-header-row d-flex">
    <div class="header-shift">
      <input :class="{'field-error': validation.hasError('shiftName')}"
             type="text"
             placeholder="Enter Shift Name"
             class="shift-header-input"
             v-model="shiftName"
             @input="setShiftName(); validateShiftName()">
    </div>
    <div class="error-message">{{validation.firstError('shiftName')}}</div>
    <close-btn v-if="shift_groups.length != 1"
               class="remove-shift"
               @click="removeShift"></close-btn>
  </div>
  <shift-time-row-list :timeRowsList="time_rows"
                       :shiftId="shiftId"></shift-time-row-list>
  <div class="add-shift-row d-flex"
       v-if="shiftId==shift_groups[shift_groups.length-1].shift_id">
    <div class="header-shift">
      Add New Shift
    </div>
    <add-button class="add-btn-right"
                @click="addShift"></add-button>
  </div>
  <div class="add-shift-row d-flex"
       v-else></div>
</div>
</template>
<script>
import {
  mapActions,
  mapGetters,
  mapMutations
} from 'vuex';
import {
  Validator
} from "simple-vue-validator";
import {
  $eventBus
} from '../../../../../main';
export default {
  mixins: [require("simple-vue-validator")
    .mixin
  ],
  props: {
    shiftId: Number
  },
  data: function() {
    return {
      shiftName: "",
    }
  },
  validators: {
    "shiftName" (value) {
      return Validator.value(value)
        .required();
    },
  },
  watch: {
    shift_groups: {
      handler(value) {
        for (let shift of value) {
          if (shift.shift_id == this.shiftId) {
            shift.name && (this.shiftName = shift.name);
          }
        }
      },
      deep: true,
      immediate: true
    },
    time_rows: {
      handler: function(rows) {
        if (rows[0] && rows[0].name) {
          this.shiftName = rows[0].name;
        }
      },
      deep: true,
      immediate: true
    }
  },
  computed: {
    ...mapGetters(['shift_groups']),
    time_rows: function() {
      let tr;
      this.shift_groups.forEach(item => {
        if (item.shift_id == this.shiftId) {
          tr = item.time_rows;
        }
      });
      return tr;
    },
  },
  mounted() {
    $eventBus.$on('clearData', () => {
      this.shiftName = "";
      this.validation.reset();
    });
    $eventBus.$on('nextSlide', () => {
      this.validateOnScroll()
    });
    window.addEventListener('mousewheel', this.validateOnScroll);
  },
  destroyed() {
    window.removeEventListener('mousewheel', this.validateOnScroll);
  },
  methods: {
    ...mapMutations(['addNewShiftGroup', 'updateShiftGroup', 'addNewShiftTimeRow', 'removeShiftGroup']),
    validateOnScroll() {
      this.$validate()
    },
    addShift() {
      this.addNewShiftGroup({
        shift_id: this.shiftId + 1,
        listBusyDays: [],
        time_rows: [],
        timeRowCount: 0,
      });
      this.addNewShiftTimeRow(this.shiftId + 1);
    },
    removeShift() {
      this.removeShiftGroup(this.shiftId);
    },
    setShiftName() {
      this.updateShiftGroup({
        id: this.shiftId,
        value: this.shiftName
      });
    },
    validateShiftName() {
      let valid = false;
      if (this.shiftName) {
        valid = true;
      }
      $eventBus.$emit('validateShiftName', valid);
    }
  },
  components: {
    shiftTimeRowList: require('../ShiftsTimeRowList'),
    addButton: require('../../../../Common/ButtonPlus'),
    closeBtn: require('../../../../Common/CloseBtn')
  }
}
</script>
<style lang="scss" src="./style.scss" scoped></style>
