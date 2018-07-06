<template>
<div class="wrap__cntxbtn">
	<button class="cntx-btn" @click="showCntxMenu = !showCntxMenu" @blur="showCntxMenu = false">
		<svg class="icn-ellipsis"><use xlink:href="images/icons-sprite.svg#icn-ellipsis"></use></svg>
	</button>
	<ul v-if="showCntxMenu" class="cntxbtn__menu">
		<li class="cntxbtn__menu-item" @click="$emit('markRead')">Mark Read</li>
		<li class="cntxbtn__menu-item" @click="$emit('markUnread')">Mark Unread</li>
		<li class="cntxbtn__menu-item" @click="$emit('export')">Export</li>
		<li class="cntxbtn__menu-item" @click="$emit('archive')">Archive</li>
		<li class="cntxbtn__menu-item" @click="$emit('delete')">Delete</li>
	</ul>
</div>
</template>
<script>
	import 'images/sprites/icn-ellipsis.svg'

	export default {
		data: () => ({
			showCntxMenu: false,
		})
	}
</script>
<style lang='scss' scoped>
	.cntx-btn {
		display: flex;

		width: 35px;
		height: 35px;

		align-items: center;
		justify-content: center;

		background-color: #ecf4f6;
		border-radius: 50%;

		border: none;
		outline: none;

		cursor: pointer;

		&:hover {
			background-color: darken(#e9eff2, 10);
		}
	}

	.icn-ellipsis {
		width: 3px;
		height: 15px;

		fill: #627680;
	}

	.cntxbtn__menu {
		width: 180px;

		margin: 0;
		padding: 10px 0;

		list-style-type: none;

		background-color: #fff;
		box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
		border-radius: 2px;

		position: absolute;
		top: 40px;
		left: -150px;

		z-index: 10;

		&-item {
			padding: 5px 25px;

			color: #505d63;
			font-size: 15px;

			cursor: pointer;

			&:hover {
				background-color: #e9f1f5;
			}
		}
	}
</style>