<template lang="html">
  <div class="">
    <h2>Notification</h2>

    <div class="table-wraper">
      <table class="m-table">
        <thead>
          <th>Type</th>
          <th>Subject</th>
          <th>Date</th>
        </thead>
        <tbody>
          <tr v-for="notification in notifications" :key="notification.id">
            <td>{{notification.type}}</td>
            <td>{{notification.report.title}}</td>
            <td>{{$moment(notification.created).fromNow()}}</td>
          </tr>
        </tbody>
      </table>

      <button class="load-more">load more</button>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    notifications: [],
    page: 1
  }),
  created() {
    this.$http.get(`api/v1/users/${this.$auth.user().id}/notifications?page=${this.page}`).then(
      res => this.notifications = res.body.items
    )
  }
}
</script>

<style lang="scss" src="./style.scss"></style>
