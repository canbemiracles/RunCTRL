<template lang="html">
  <div class="wrapper">
    <div class="top">
      <slot />
    </div>
    <div>
      <ul>
        <li v-for="report in reports" v-b-toggle="'collapse' + report.id" >
          <div class="list-wrapper" :class="type">
            <div class="circle-wrapper">
              <div class="inner d-flex">  
                <div class="circle" />
              </div>
            </div>
            <div class="list-content d-flex flex-column">   
              <div class="list-item">
                <div class="time">
                  {{report.created | moment(twelvehour ? 'HH:MMA' : 'HH:MM')}}
                </div>
                <div class="title">
                  {{report.title}}
                </div>
                <div class="name">
                  {{report.branch_manager.first_name}} {{report.branch_manager.last_name}}
                </div>
              </div>
              <b-collapse :id="'collapse' + report.id">
                <p class="description">{{report.description}}</p>
              </b-collapse>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    type: String,
    reports: Array,
    twelvehour: Boolean
  },
  mounted() {
  }
}
</script>

<style lang="scss" scoped>
.icon {
    display: inline-block;
}
.top {
    display: flex;
    align-items: center;
}
h2 {
    display: inline-block;
    font-size: 22px;
    font-weight: 400;
    margin-bottom: 0;
    margin-left: 10px;
}
.wrapper {
    margin-bottom: 30px;
}
ul {
  padding: 0;
  margin-top: 30px;
  display: flex;
  flex-direction: column;
  li {
    flex: 1;
    flex-basis: 50px;
    line-height: 50px;
    list-style: none;
    color: #505d63;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    .list-wrapper {
      display: flex;
      height: 100%;
    }
    .list-item {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: inset 0 -1px 0 #e6ebed;
      .title{
        flex-grow: 1;
        margin-left: 10px;
      }
    }

    .description {
      color: #505d63;
      font-size: 16px;
      font-weight: 400;
      line-height: 24px;
      padding: 15px 15px 15px 0;
      margin: 0;

    }
  }
  .list-content {
    width: 100%;
    margin-left: 10px;
  }
  .circle-wrapper{
    display: flex;
    flex-direction: column;
    .inner{
      flex-grow: 1;
      position: relative;
      &:before, &:after {
        display: block;
        width: 1px;
        height: 12px;
        margin: auto;
        content: '';
        background-color: #f53f3f;
        opacity: 0.5;
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
      }
      &:after {
        bottom: 0;
        height: calc(100% - 39px);
        top: auto;
      }
    }
    
    .circle {
      height: 21px;
      width: 21px;
      border: 2px solid #f53f3f;
      border-radius: 50%;
      background: white;
      background-color: #f53f3f;
      position: relative;
      margin-top: 15px;
      &:after {
        content: '';
        position: absolute;
        height: 8px;
        width: 8px;
        border: solid white;
        border-width: 0 2px 2px 0;
        top: 50%;
        left: 50%;
        margin-left: -5px;
        margin-top: -3px;
        transform: rotate(-135deg);
        transform-origin: 50% 50%;
      }
    }

  }

  .commodity {
    .circle-wrapper  {
      .inner{
        &:before, &:after {
          background-color: #4ba6f8;
        }
      }
      .circle {
        border: 2px solid #4ba6f8;
        background-color: #4ba6f8;
      }
    }
  }

  .collapsed {
    .circle-wrapper {
      .circle {
        background: white;
        &:after {
          top: 6px;
          border: solid #f53f3f;
          border-width: 0 2px 2px 0;
          transform: rotate(45deg);
        }
      }
    }
    .commodity {
      .circle-wrapper {
        .circle {
          background: white;
          &:after {
            top: 6px;
            border: solid #4ba6f8;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
          }
        }
      }
    }
  }

}
</style>
