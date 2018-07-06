<template lang="html">
  <div class="">
    <div class="top row">
      <div class="col col-md-4 card-wrapper">
        <div class="b-card">
          <svg class="icon"><use xlink:href="images/icons-sprite.svg#checked"></use></svg>
          <h5>Your commercial plan is</h5>
          <div class="mark">Enterprise</div>
        </div>
      </div>
      <div class="col col-md-4 card-wrapper">
        <div class="b-card">
          <svg class="icon"><use xlink:href="images/icons-sprite.svg#permonth"></use></svg>
          <h5>You pay every month</h5>
          <div class="mark">30$</div>
        </div>
      </div>
      <div class="col col-md-4 card-wrapper">
        <div class="b-card">
          <svg class="icon"><use xlink:href="images/icons-sprite.svg#expire"></use></svg>
          <h5>You subscription will expire on</h5>
          <div class="mark">March 05, 2018</div>
        </div>
      </div>
    </div>

    <h2>Billing</h2>
    <h3>Payment Method</h3>
    <div class="table-wraper">
      <table class="m-table">
        <tr v-for="i in 2" :key="i">
          <td><svg class="table-icon"><use xlink:href="images/icons-sprite.svg#visa"></use></svg></td>
          <td>Visa</td>
          <td>455689******0302</td>
          <td>ended in 2021</td>
          <td>
            <div class="mark">Primary</div>
            <!-- <svg class="edit-icon"><use xlink:href="images/icons-sprite.svg#edit"></use></svg> -->
            <close-button class="close" />
          </td>
        </tr>
        <tr v-for="i in 2" :key="i*3">
          <td><svg class="table-icon"><use xlink:href="images/icons-sprite.svg#mastercard"></use></svg></td>
          <td>MasterCard</td>
          <td>455689******0302</td>
          <td>ended in 2021</td>
          <td>
            <div class="mark">Primary</div>
            <!-- <svg class="edit-icon"><use xlink:href="images/icons-sprite.svg#edit"></use></svg> -->
            <close-button class="close" />
          </td>
        </tr>
      </table>
    </div>


    <h3>Promocode</h3>
    <div class="promocode">
      <div class="">
        <input type="text" name="" value="" placeholder="Enter promocode">
      </div>
      <div class="">
        <p>So when its time to finally get your children that very first telescope, you want to make sure it’s just the right thing.</p>
      </div>
    </div>


    <h3>Payment History</h3>
    <div class="table-wraper">
      <table class="m-table">
        <thead>
          <th>Order Id</th>
          <th>Date</th>
          <th>Description</th>
          <th>Amount</th>
          <th></th>
        </thead>
        <tbody>
          <tr v-for="i in 7" :key="i">
            <td>#12345</td>
            <td>03 Dec 2017</td>
            <td>Payment history description</td>
            <td>$272</td>
            <td class="controls">View
              <svg class="edit-icon"><use xlink:href="images/icons-sprite.svg#download"></use></svg>
            </td>
          </tr>
        </tbody>
      </table>

      <button class="load-more">load more</button>``
    </div>
  </div>
</template>

<script>
import 'images/sprites/billing/checked.svg'
import 'images/sprites/billing/permonth.svg'
import 'images/sprites/billing/expire.svg'
import 'images/sprites/billing/visa.svg'
import 'images/sprites/billing/mastercard.svg'
import 'images/sprites/billing/edit.svg'
import 'images/sprites/billing/download.svg'
export default {
  data: () => ({

  }),
  components: {
    closeButton: require('../../Common/CloseBtn'),
  }
}
</script>

<style lang="scss" src="./style.scss" scoped></style>
<style lang="scss" src="../table.scss"></style>
