<template>
  <div class="form-inline d-flex justify-content-around">
    <div class="form-check mr-3 pr-2 pl-2 border-right border-left">
      <input type="checkbox" class="form-check-input" @change="iconsChecked">
      <label class="form-check-label">Скрыть иконки</label>
    </div>
    <div class="form-check mr-3 pr-2 border-right">
      <input type="checkbox" class="form-check-input" @change="dueDateChecked">
      <label class="form-check-label">Скрыть дату выполнения</label>
    </div>
    <div class="form-check mr-3 pr-2 border-right">
      <input type="checkbox" class="form-check-input" @change="completedChecked">
      <label class="form-check-label">Показать завершенные</label>
    </div>
    <div class="form-check mr-3 pr-2 border-right">
      <input type="checkbox" class="form-check-input" @change="showAllStepsChecked">
      <label class="form-check-label">Раскрыть подзадачи</label>
    </div>
    <div>
      <span>Показать</span>
      <select class="form-control form-control-sm" @change="changeLimit">
        <option value="5" selected>5</option>
        <option value="15">15</option>
        <option value="">Все</option>
      </select>
      <span>задач</span>
    </div>
  </div>
</template>

<script>
  export default {
    methods: {
      iconsChecked(){
        Fire.$emit('changeIconsView');
      },
      dueDateChecked(){
        Fire.$emit('changeDueDateView');
      },
      completedChecked(){
        this.$emit('showAllTasks');
      },
      changeLimit(event){
        this.$emit('setLimit', event.target.value);
      },
      showAllStepsChecked(){
        Fire.$emit('changeStepsView');
      }
    },
  }
</script>




