'use strict';

(() => {

    // toggle extra fee fields
    const extraFeeType = document.querySelector('.itella-cod-extra-fee-type');
    const extraFeeAmount = document.querySelector('.itella-cod-extra-fee-amount');

    const extraFeeAmountOption = extraFeeAmount.parentElement.parentElement.parentElement;
    const extraFeeTaxOption = extraFeeAmountOption.nextElementSibling;
    const extraFeeNochargeOption = extraFeeTaxOption.nextElementSibling;

    if (extraFeeType.value === "disabled") {
        extraFeeAmountOption.classList.toggle('d-none');
        extraFeeTaxOption.classList.toggle('d-none');
        extraFeeNochargeOption.classList.toggle('d-none');
    }

    extraFeeType.addEventListener('change', function () {
        if (extraFeeType.value === 'disabled' && !extraFeeType.classList.contains('d-none')) {
            extraFeeAmountOption.classList.add('d-none');
            extraFeeTaxOption.classList.add('d-none');
            extraFeeNochargeOption.classList.add('d-none');
        } else {
            extraFeeAmountOption.classList.remove('d-none');
            extraFeeTaxOption.classList.remove('d-none');
            extraFeeNochargeOption.classList.remove('d-none');
        }
    })

})();