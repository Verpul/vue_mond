<template>
  <div>
    <hr class="mb-1 mt-1">
    <ul class="pl-1 list-unstyled">
      <li v-for="step in steps" :key="step.id">
        <i class="fas fa-times text-danger" v-if="step.active" 
          @click="changeStepStatus(step.id, step.todo_id, false)" 
          style="cursor: pointer;"></i>
        <i class="fas fa-check text-success" 
          @click="changeStepStatus(step.id, step.todo_id, true)"
          style="cursor: pointer;" 
          v-else></i>
        <span :style="!step.active ? 'text-decoration: line-through': ''">{{step.step}}</span>
        <div class="tools">
          <i class="fas fa-edit c-orange" @click="$emit('openEditStep', step)"></i>
          <i class="fas fa-trash c-orange" @click="deleteStep(step.id, step.todo_id)"></i>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
  export default {
    methods: {
      deleteStep(stepId, todoId){
        this.$swal({
          title: 'Вы уверены?',
          text: 'Данный пункт будет удален',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Да, удалить!',
          cancelButtonText: 'Отмена'
          }).then((result) => {
              if (result.value) {
                axios.delete('api/todo/step/' + stepId).then(() => { 

                  Fire.$emit('loadSteps', todoId);

                  this.$swal({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    icon: 'success',
                    title: 'Пункт удален!'
                  });   
                  }).catch(() => {
                      this.$swal(
                          'Действие отменено',
                          'Что-то пошло не так',
                          'error'
                      )
                  });
              }       
        })
      },
      // Сменить статус шага на выполнено и обратно
      changeStepStatus(stepId, todoId, currentStatus){
        axios.put('api/todo/step/active/' + stepId, {
          currentStatus: currentStatus
        }).then(() => {
          Fire.$emit('loadSteps', todoId);

          this.$swal({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            icon: 'success',
            title: 'Выполнено!'
          });   
        }).catch(() => {
            this.$swal(
                'Действие отменено',
                'Что-то пошло не так',
                'error'
            )
        });
      }
    },
    props: [
      'steps'
    ]
  }
</script>

<style scoped>
  .c-orange {
    color: #f6993f;
  }
</style>



