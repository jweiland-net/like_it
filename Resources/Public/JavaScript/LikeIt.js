document.addEventListener('DOMContentLoaded', function () {
  /**
   * Process ajax result from actions check OR toggle.
   *
   * @param likeItContainer
   * @param result
   */
  function processResult (likeItContainer, result) {
    likeItContainer.querySelector('.tx-likeit-amount-of-likes .amount-of-likes').textContent = result.amountOfLikes;
    if (result.liked === true) {
      likeItContainer.querySelector('.like-message').textContent = likeItContainer.getAttribute('data-message-like');
      likeItContainer.querySelector('.tx-likeit-like').classList.add('liked');
    } else {
      likeItContainer.querySelector('.like-message').textContent = likeItContainer.getAttribute('data-message-no-like');
      likeItContainer.querySelector('.tx-likeit-like').classList.remove('liked');
    }
  }

  /**
   * @param likeItContainer
   * @param {string} action
   */
  function updateAmountOfLikesForContainer(likeItContainer, action) {
    let table = likeItContainer.getAttribute('data-table');
    let uid = parseInt(likeItContainer.getAttribute('data-uid'));

    fetch('/index.php?eID=tx_likeit_like&action=' + action + '&table=' + table + '&uid=' + uid)
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        processResult(likeItContainer, data);
      })
      .catch(function (err) {
        console.warn('Error while fetching AJAX response of TYPO3 extension like_it.', err);
      });
  }

  // Initially check if user has liked and refresh amount of like
  document.querySelectorAll('.tx-likeit-container').forEach(function (likeItContainer) {
    updateAmountOfLikesForContainer(likeItContainer, 'check');
  });

  // Toggle like and refresh amount of like
  document.querySelectorAll('.tx-likeit-like').forEach(function (likeItButton) {
    likeItButton.addEventListener('click', function (e) {
      e.preventDefault();
      let likeItContainer = likeItButton.closest('.tx-likeit-container');
      updateAmountOfLikesForContainer(likeItContainer, 'toggle');
    });
  });
});
