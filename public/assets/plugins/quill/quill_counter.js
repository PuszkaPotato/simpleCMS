class Counter {
    constructor(quill, options) {
      this.quill = quill;
      this.options = options;
      this.container = document.querySelector(options.container);
      quill.on('text-change', this.update.bind(this));
      this.update();  // Account for initial contents
    }
  
    calculate() {
      let text = this.quill.getText();
      if (this.options.unit === 'word') {
        text = text.trim();
        // Splitting empty text returns a non-empty array
        return text.length > 0 ? text.split(/\s+/).length : 0;
      } else {
        return text.length-1;
      }
    }
    
    update() {
      var length = this.calculate();
      var label = this.options.unit;
      if (length !== 1) {
        label += 's';
      }
      this.container.innerText = length + ' ' + label;
    }
  }
  
  Quill.register('modules/counter', Counter);