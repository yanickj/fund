(function(document) {
    'use strict';

    const newCampaignEl = document.getElementById('new-campaign');
    const closeModalEl = document.getElementById('close-modal');
    const modalWrapperEl = document.getElementById('new-campaign-modal');
    const newProjectFormEl = document.getElementById('new-project-form');

    newCampaignEl.addEventListener('click', handleNewCampaignClick);
    closeModalEl.addEventListener('click', handleCloseModalClick);

    function handleNewCampaignClick(e) {
        e.preventDefault();
        e.stopPropagation();

        modalWrapperEl.classList.remove('project-new__modal--hidden');
    }

    function handleCloseModalClick(e) {
        modalWrapperEl.classList.add('project-new__modal--hidden');
    }
})(document);
