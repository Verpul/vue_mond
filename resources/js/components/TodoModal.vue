<template>
<div class="modal fade show d-block" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"
				@click="$emit('close')">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<form>
				  <div class="modal-body">
						<div class="form-group">
							<label>Тема</label>
						  <input v-model="form.title" type="text" name="title"
							class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
						  <has-error :form="form" field="title"></has-error>
						</div>
						<div class="form-group">
							<label>Текст задачи</label>
						  <textarea v-model="form.task" class="form-control" rows="3"></textarea>
						  <has-error :form="form" field="email"></has-error>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col">
							    <label>Приоритет</label>
							    <select class="form-control" v-model="form.priority">
							      <option value="Низкий">Низкий</option>
							      <option value="Средний">Средний</option>
							      <option value="Высокий">Высокий</option>
							    </select>
						  	</div>
						  	<div class="col">
							    <label class="text-nowrap">Выполнить до (необязательно)</label>
							    <div class="input-group">
							    	<div class="input-group-prepend">
							    		<div class="input-group-text"><i class="fas fa-user-clock"></i></div>
							    	</div>
							    	<date-picker v-model="form.due_date" :config="options"></date-picker>
							    </div>
						    </div>
					    </div>
					  </div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal" @click="$emit('close')">Закрыть</button>
					<button type="submit" class="btn btn-success">Сохранить</button>
				  </div>
			  </form>
		  </div>
		</div>
  </div>
</div>
</template>

<script>
	import datePicker from 'vue-bootstrap-datetimepicker';
	import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

	export default {
		name: "ModalComponent",
		data(){
			return {
				date: new Date(),
				options: {
					format: 'DD/MM/YYYY HH:mm',
					locale: this.$moment.locale(),
					icons: {
			      time: 'far fa-clock',
			      date: 'far fa-calendar',
			      up: 'fas fa-arrow-up',
			      down: 'fas fa-arrow-down',
			      previous: 'fas fa-chevron-left',
			      next: 'fas fa-chevron-right',
			      today: 'fas fa-calendar-check',
			      clear: 'far fa-trash-alt',
			      close: 'far fa-times-circle'
    			},
				},
				form: new Form({
					id: '',
					title: '',
					task: '',
					priority: 'Низкий',
					due_date: null
				})
			}
		},
		components: {
			datePicker
		}
	}
</script>