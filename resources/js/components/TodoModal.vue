<template>
<div class="modal fade show d-block" id="todoModal" tabindex="-1" role="dialog" aria-labelledby="todoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="todoModalLabel" v-if="!modalData.editMode">Создание задачи</h5>
			<h5 class="modal-title" id="todoModalLabel" v-else>Редактирование задачи</h5>
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
						  <textarea v-model="form.task" class="form-control" rows="3"
						  :class="{ 'is-invalid': form.errors.has('task') }"></textarea>
						  <has-error :form="form" field="task"></has-error>
						</div>
						<div class="form-group">
					    <label>Выполнить до (необязательно для заполнения)</label>
					    <div class="input-group">
					    	<div class="input-group-prepend">
					    		<div class="input-group-text"><i class="fas fa-user-clock"></i></div>
					    	</div>
					    	<date-picker v-model="form.due_date" :config="options"></date-picker>
					    </div>
				    </div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" @click="$emit('close')">Закрыть</button>
					<button type="submit" class="btn btn-success" 
						@click.prevent="modalData.editMode ? editTodo() : createTodo()" 
						>Сохранить</button>
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
				options: {
					format: 'DD.MM.YYYY HH:mm',
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
    			showClose: true,
    			showClear: true
				},
				form: new Form({
					id: '',
					title: '',
					task: '',
					due_date: null
				})
			}
		},
		methods: {
			createTodo(){
				//Форматируем дату для MySQL
				if(this.form.due_date !== null){
					this.form.due_date = this.$moment(this.form.due_date, 'DD.MM.YYYY HH:mm')
						.format('YYYY/MM/DD HH:mm');
				};

				this.form.post('/api/todo')
	      .then(() => {
	        this.$emit('loadTodos');
	        this.$emit('close');

	        this.$swal({
	            toast: true,
	            position: 'top-end',
	            showConfirmButton: false,
	            timer: 3000,
	            icon: 'success',
	            title: 'Новая задача добавлена!'
	        });
	      })
			},
			editTodo(){
				//Форматируем дату для MySQL
				if(this.form.due_date !== null){
					this.form.due_date = this.$moment(this.form.due_date, 'DD.MM.YYYY HH:mm')
						.format('YYYY/MM/DD HH:mm');
				};

				this.form.put('/api/todo/'+this.form.id)
	      .then(() => {
	        this.$emit('loadTodos');
	        this.$emit('close');

	        this.$swal({
	            toast: true,
	            position: 'top-end',
	            showConfirmButton: false,
	            timer: 3000,
	            icon: 'success',
	            title: 'Задача изменена!'
	        });
	      })
			},
		},
		
		created() {
			this.form.clear();
      this.form.reset();

      //Если это редактирование - заполняем поля
			if(this.modalData.editMode){
				this.form.fill(this.modalData.todo);
				//Переводим из строки в дату для input type=date
				this.form.due_date = new Date(this.modalData.todo.due_date);
			}
		},
		components: {
			datePicker
		},
		props: [
			'modalData'
		]
	}
</script>