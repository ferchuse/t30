


<style> 
	.seat {
  width: 50px;
  min-height: 5em;
  margin: 1rem 0 0 1rem;
  background-color: #29e;
  color: white;
  border-radius: 0.75em;
  padding: 4%;
  touch-action: none;
  user-select: none;
	display:flex;
  transform: translate(0px, 0px);
	}
	
	
	.seats {
	display: flex;
	flex-direction: row;
	flex-wrap: nowrap;
	justify-content: flex-start;
	padding-left: 40px;
	}
	.seat {
	display: flex;
	flex: 0 0 14.28571429%;
	padding: 5px;
	position: relative;
	}
	
</style>

<div class="seat--container">
	<div class="seats">
		<div  class="draggable seat">
			1 
		</div>
		<div  class="draggable seat">
			2 
		</div>
	</div>
</div>





<script src="https://unpkg.com/interactjs/dist/interact.min.js"></script>

<script>
	// target elements with the "draggable" class
	interact('.draggable')
	.draggable({
	// enable inertial throwing
	inertia: true,
	// keep the element within the area of it's parent
	modifiers: [
	interact.modifiers.restrictRect({
	restriction: 'parent',
	endOnly: true
	})
	],
	// enable autoScroll
	autoScroll: true,
	
	listeners: {
	// call this function on every dragmove event
	move: dragMoveListener,
	
	// call this function on every dragend event
	end (event) {
	var textEl = event.target.querySelector('p')
	
	textEl && (textEl.textContent =
	'moved a distance of ' +
	(Math.sqrt(Math.pow(event.pageX - event.x0, 2) +
	Math.pow(event.pageY - event.y0, 2) | 0))
	.toFixed(2) + 'px')
	}
	}
	})
	
	function dragMoveListener (event) {
  var target = event.target
  // keep the dragged position in the data-x/data-y attributes
  var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx
  var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy
	
  // translate the element
  target.style.transform = 'translate(' + x + 'px, ' + y + 'px)'
	
  // update the posiion attributes
  target.setAttribute('data-x', x)
  target.setAttribute('data-y', y)
	}
	
	// this function is used later in the resizing and gesture demos
	window.dragMoveListener = dragMoveListener
</script>

