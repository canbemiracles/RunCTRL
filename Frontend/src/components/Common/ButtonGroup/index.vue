<template>
<div class="ml-auto d-flex align-items-center">
	<b-button-group class="button-group">
		<b-button @click="$emit('markRead')">Mark Read</b-button>
		<span class="btn-separator"></span>
		<b-button @click="$emit('markUnread')">Mark Unread</b-button>
		<span class="btn-separator"></span>
		<b-button @click="showExportDialog = !showExportDialog">Export</b-button>
		<div v-if="showExportDialog" class="export-dialog row">
			<div class="col-4">
				<svg class="icon">
					<use xlink:href="images/icons-sprite.svg#file-word"></use>
				</svg>
				<p>Word</p>
			</div>
			<div class="col-4">
				<svg class="icon">
					<use xlink:href="images/icons-sprite.svg#file-excel"></use>
				</svg>
				<p>Exel</p>
			</div>
			<div class="col-4">
				<span class="icon-csv-text">CSV</span>
				<svg class="icon icon-csv">
					<use xlink:href="images/icons-sprite.svg#file-o"></use>
				</svg>
				<p>CSV</p>
			</div>
		</div>
		<span class="btn-separator"></span>
		<b-button @click="$emit('archive')">Archive</b-button>
		<span class="btn-separator"></span>
		<b-button @click="$emit('delete')">Delete</b-button>
		<span class="btn-separator"></span>
		<b-button>
			<svg class="icon-close">
				<use xlink:href="images/icons-sprite.svg#close-usage"></use>
			</svg>
		</b-button>
	</b-button-group>
</div>
</template>
<script>
	import 'images/sprites/file-word.svg'
	import 'images/sprites/file-excel.svg'
	import 'images/sprites/file-o.svg'

	export default {
		data: () => ({
			showExportDialog: false,
			showArchiveDialog: false,
		})
	}
</script>
<style lang='scss' scoped>
	.button-group {
		height: 36px;
		border-radius: 2px;
		button {
			background: #e9f1f5;
			color: #627680;
			border: none;
		}
	}

	.button-group .btn:active, .button-group .btn:focus {
		box-shadow: none;
	}

	.btn-separator {
		background: #e9f1f5;
		&:after {
			content: ' ';
			display: flex;
			margin: 8px 0;
			background: #b9c8cf;
			height: 20px;
			width: 1px;
		}
	}

	.export-dialog {
		text-align: center;
		position: absolute;
		background: white;
		color: #8ba4b1;
		font-size: 12px;
		font-weight: 700;
		top: 45px;
		left: 155px;
		height: 100px;
		border-radius: 2px;
		padding: 20px 15px;
		box-shadow: 0 0 10px 0 rgba(28, 60, 77, 0.2),
		0 10px 20px rgba(28, 60, 77, 0.3);
		z-index: 2;
		div {
		}
	}

	.export-dialog:before {
		content: '';
		position: absolute;
		width: 0;
		height: 0;
		margin: auto;
		border-width: 10px;
		border-style: solid;
		border-color: transparent transparent #ffff transparent;
		top: -20px;
		left: 0;
		right: 0;
	}

	.archive-dialog {
		text-align: center;
		position: absolute;
		background: white;
		color: #8ba4b1;
		font-size: 12px;
		font-weight: 700;
		top: 45px;
		left: 230px;
		height: 100px;
		border-radius: 2px;
		padding: 20px 15px;
		box-shadow: 0 0 10px 0 rgba(28, 60, 77, 0.2),
		0 10px 20px rgba(28, 60, 77, 0.3);
		z-index: 2;
		div {
		}
	}

	.archive-dialog:before {
		content: '';
		position: absolute;
		width: 0;
		height: 0;
		margin: auto;
		border-width: 10px;
		border-style: solid;
		border-color: transparent transparent #ffff transparent;
		top: -20px;
		left: 0;
		right: 0;
	}

	/*
	 * ICONS
	 */

	.icon-close {
		width: 12px;
		height: 12px;
		fill: #627680;
	}

	.icon {
		width: 35px;
		height: 38px;

		fill: #8ba4b1;
	}
	.icon-csv {
		position: relative;
	}
	.icon-csv-text {
		margin: auto;

		font-size: 10px;
		color: #8ba4b1;

		position: absolute;
		top: 15px;
		right: 23px;
	}
</style>