const testKK = '1404051205980001';
const regKK = /^(1[1-9]|[2-9]\d)\d{4}(0[1-9]|[12]\d|3[01])(0[1-9]|1[0-2])\d{6}$/;
console.log('KK Match:', regKK.test(testKK));
