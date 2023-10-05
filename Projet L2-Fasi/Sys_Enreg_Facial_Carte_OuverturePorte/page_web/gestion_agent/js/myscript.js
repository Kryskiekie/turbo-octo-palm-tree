function moveDiv() {
    var div = document.querySelector('.moveable-div');
    var sidebar = document.querySelector('.sidebar');
    var closeButton = document.querySelector('.close-button');
    if (div.style.transform === 'translateX(-100px)') {
      div.style.transform = 'translateX(0)';
      sidebar.style.transform = 'translateX(-200px)';
      closeButton.style.display = 'none';
    } else {
      div.style.transform = 'translateX(-100px)';
      sidebar.style.transform = 'translateX(0)';
      closeButton.style.display = 'block';
    }
  }
  