<template>
<div class="modal fade show d-block" id="todoModal" tabindex="-1" role="dialog" aria-labelledby="todoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="todoModalLabel" v-if="!stepModalData.editMode">Добавление шагов выполнения</h5>
			<h5 class="modal-title" id="todoModalLabel" v-else>Редактирование шага выполнения</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"
				@click="$emit('closeStepModal')">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<form>
				  <div class="modal-body">
						<div class="form-group">
						  <textarea v-model="form.step" class="form-control" rows="5"
						  :class="{ 'is-invalid': form.errors.has('step') }"></textarea>
						  <has-error :form="form" field="step"></has-error>
						</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" 
						@click="$emit('closeStepModal')">Закрыть</button>
					<button type="submit" class="btn btn-success" 
						@click.prevent="stepModalData.editMode ? editStep() : createStep()" >
						Сохранить
					</button>
				  </div>
			  </form>
		  </div>
		</div>
  </div>
</div>
</template>

<script>
	export default {
		name: 'TodoStepModal',
		data(){
			return {
				form: new Form({
					id: '',
					todo_id: '',
					step: ''
				})
			}
		},
		methods: {
			createStep(){
				this.form.post('/api/todo/step', {
					params: {
						todoId: this.stepModalData.todoId
					}
				}).then(() => {
	        
	        this.$emit('stepCreated', this.stepModalData.todoId);
	        Fire.$emit('loadSteps', this.stepModalData.todoId);
	        this.$emit('closeStepModal');

	        this.$swal({
	            toast: true,
	            position: 'top-end',
	            showConfirmButton: false,
	            timer: 3000,
	            icon: 'success',
	            title: 'Новый шаг выполнения добавлен!'
	        });
	      })
			},
			editStep(){
				this.form.post('/api/todo/step/' + this.form.id)
	      	.then(() => {
	        
	        this.$emit('stepCreated', this.form.todo_id);
	        Fire.$emit('loadSteps', this.form.todo_id);
	        this.$emit('closeStepModal');

	        this.$swal({
	            toast: true,
	            position: 'top-end',
	            showConfirmButton: false,
	            timer: 3000,
	            icon: 'success',
	            title: 'Новый шаг выполнения добавлен!'
	        });
	      })
			}
		},
		created() {
			this.form.clear();
			this.form.reset();

			// Редактирование - заполняем поля, создание - передаем только Todo id
			if(this.stepModalData.editMode){
				this.form.fill(this.stepModalData.step);
			}else{
				this.form.todo_id = this.stepModalData.todoId;
			}
		},
		props: [
			'stepModalData'
		]
	}
</script>