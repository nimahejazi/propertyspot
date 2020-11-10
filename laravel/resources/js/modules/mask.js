require('inputmask');
// console.log(Inputmask);
module.exports = (path) => {
    // make sure we are in listing page
    if (/^\/users\/profile\/?$/.test(path)) {
        if (document.getElementById('phone')) {
            Inputmask({
                mask: '(999) 999-9999',
                rightAlign: false,
                jitMasking: true,
            }).mask(document.getElementById('phone'));

        }
        return;
    }
    if (/^\/users\/new-listing\/?$/.test(path))
    if (document.getElementById('price') !== null) {
        Inputmask({
            alias: 'decimal',
            groupSeparator: ',',
            autoGroup: true,
            digitalOptional: false,
            placeholder: '',
            rightAlign: false,
            autoUnmask: true,
        }).mask(document.getElementById('price'));

        Inputmask({
            mask: '9999',
            rightAlign: false,
        }).mask(document.getElementById('year_built'));
        return;
    }

    if (path === 'simple') {
        Inputmask({
            mask: '(999) 999-9999',
            rightAlign: false,
            jitMasking: true,
        }).mask(document.getElementById('phone'));
        Inputmask({
            mask: '(999) 999-9999',
            rightAlign: false,
            jitMasking: true,
        }).mask(document.getElementById('phone-modal'));
        return;
    }
}
