const presentationContainer = document.querySelector('.presentation_container');
const presentation = document.querySelector('.presentation');
const addField = document.querySelector('#add_field');

addField.addEventListener('click', e => {
	e.preventDefault();

	const presentationIndex = Array.from(document.querySelectorAll('.medicament_field')).length;
	const clone = presentation.cloneNode(true);
	
	clone.insertAdjacentHTML('beforeend', '<a class="remove_field ms-4" href="#">Retirer</a>');


	let cloneHTML = `<div class="presentation">${clone.innerHTML}</div>`;

	cloneHTML = cloneHTML.replace(/compte_rendu_presentation_(\d+)_medicament/gim, `compte_rendu_presentation_${presentationIndex}_medicament`);

	cloneHTML = cloneHTML.replace(/compte_rendu\[presentation\]\[(\d+)\]\[medicament\]/gim, `compte_rendu[presentation][${presentationIndex}][medicament]`);

	cloneHTML = cloneHTML.replace(/compte_rendu_presentation_(\d+)_quantiteOfferte/gim, `compte_rendu_presentation_${presentationIndex}_quantiteOfferte`);

	cloneHTML = cloneHTML.replace(/compte_rendu\[presentation\]\[0\]\[quantiteOfferte\]/gim, `compte_rendu[presentation][${presentationIndex}][quantiteOfferte]`);

	presentationContainer.insertAdjacentHTML('beforeend', cloneHTML);

	const retirerField = document.querySelector('.presentation:last-child .remove_field');
	retirerField.addEventListener('click', e => {
		e.preventDefault();
		presentationContainer.removeChild(e.target.parentNode);
	});
});