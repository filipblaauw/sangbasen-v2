<template>
  <div style="position:relative;" >
    <input class="form-control" :placeholder="trans('fish.species')" type="text"
      :value="value" @input="updateValue($event.target.value)"
      @keydown.enter = 'enter'
      @keydown.down = 'down'
      @keydown.up = 'up'
    >
    <div class="dropdown-menu" style="width:100%;" v-bind:class="{'show-dropdown-menu':open}">
      <a href="#" class="dropdown-item"
        v-for="(suggestion, index) in matches"
        v-bind:class="{'active': isActive(index)}"
        @click="suggestionClick(index)"
      >
        {{ suggestion.species }}
      </a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
      required: true
    },
    suggestions: {
      type: Array,
      required: true
    }
  },
  data () {
    return {
      open: false,
      current: 0,
      hasInput: false
    }
  },
  computed: {
    // Filtering the suggestion based on the input
    matches () {
      return this.suggestions.filter( ( suggestion, index ) => {
        const regex = new RegExp( this.value, 'gi' );
        return suggestion.species.match( regex );
      });
    },
    openSuggestion () {
      return this.selection !== '' &&
             this.matches.length !== 0 &&
             this.open === true
    }
  },
  methods: {
    updateValue (value) {
      if (this.open === false) {
        this.open = true
        this.current = 0
      }
      if (value == '') {
        this.open = false
      }
      this.$emit('input', value)
    },
    // When enter pressed on the input
    enter () {
      this.$emit('input', this.matches[this.current].species)
      this.open = false
    },
    // When up pressed while suggestions are open
    up () {
      if (this.current > 0) {
        this.current--
      }
    },
    // When up pressed while suggestions are open
    down () {
      if (this.current < this.matches.length - 1) {
        this.current++
      }
    },
    // For highlighting element
    isActive (index) {
      return index === this.current
    },
    // When one of the suggestion is clicked
    suggestionClick (index) {
      this.$emit('input', this.matches[index].species)
      this.open = false
    }
  }
}
</script>
