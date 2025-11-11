function generateStrongPassword(length = 12) {
    if (length < 8) {
        length = 8;
    }

    const lowercase = 'abcdefghijklmnopqrstuvwxyz';
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numbers = '0123456789';
    const special = '!@#$%^&*()_+-=[]{}|:<>?';

    const allChars = lowercase + uppercase + numbers + special;

    let passwordArray = [];

    passwordArray.push(lowercase[Math.floor(Math.random() * lowercase.length)]);
    passwordArray.push(uppercase[Math.floor(Math.random() * uppercase.length)]);
    passwordArray.push(numbers[Math.floor(Math.random() * numbers.length)]);
    passwordArray.push(special[Math.floor(Math.random() * special.length)]);

    for (let i = passwordArray.length; i < length; i++) {
        passwordArray.push(allChars[Math.floor(Math.random() * allChars.length)]);
    }

    for (let i = passwordArray.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [passwordArray[i], passwordArray[j]] = [passwordArray[j], passwordArray[i]];
    }

    return passwordArray.join('');
}

function setupGeneratePasswordButton(buttonSelector, inputSelector, length = 12) {
    $(buttonSelector).on('click', function() {
        const newPassword = generateStrongPassword(length);
        $(inputSelector).val(newPassword);
    });
}

function initializePasswordGeneration(inputSelector, length = 12) {
    $(inputSelector).each(function() {
        const newPassword = generateStrongPassword(length);
        $(this).val(newPassword);
    });
}

// Example usage:

// Generate a password of length 12 when button is clicked
// setupGeneratePasswordButton('your-button-selector', 'your-input-selector', 12);

// Auto-generate passwords of length 12 for all inputs with selector you provide on page load
// initializePasswordGeneration('your-input-selector', 12);
