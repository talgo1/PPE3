const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#inputPassword');

togglePassword.addEventListener('click', e => {
	e.preventDefault();
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
	if(password.type === 'password' ){
		togglePassword.innerHTML = 'AFFICHER';
	}
	else{
		togglePassword.innerHTML = 'MASQUER';
	}
	
});
