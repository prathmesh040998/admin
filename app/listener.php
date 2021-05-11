<div id="messages">

</div>
<script>
const evtSource = new EventSource("events.php");
evtSource.onmessage = function(event) {
  const newElement = document.createElement("li");
  const eventList = document.getElementById("list");

  newElement.innerHTML = "message: " + event.data;
  document.getElementById("messages").appendChild(newElement)
  //eventList.appendChild(newElement);
}

// evtSource.addEventListener("ping", function(event) {
//   const newElement = document.createElement("li");
//   const time = JSON.parse(event.data).time;
//   newElement.innerHTML = "ping at " + time;
//   eventList.appendChild(newElement);
// });
</script>