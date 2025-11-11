const digits = [" không", " một", " hai", " ba", " bốn", " năm", " sáu", " bảy", " tám", " chín"];
const groups = ["", " nghìn", " triệu", " tỷ", " nghìn tỷ", " triệu tỷ"];

function readThreeDigitGroup(group) {
    let result = "";
    let hundreds = Number(group[0]);
    let tens = Number(group[1]);
    let units = Number(group[2]);

    if (hundreds > 0 || tens > 0 || units > 0) {
        result += digits[hundreds] + " trăm";

        if (tens > 0 || units > 0) {
            if (tens == 0) {
                result += " linh" + digits[units];
            } else {
                if (tens == 1) {
                    result += " mười";
                } else {
                    result += digits[tens] + " mươi";
                }

                switch (units) {
                    case 1:
                        result += (tens > 1) ? " mốt" : " một";
                        break;
                    case 5:
                        result += (tens == 0) ? " năm" : " lăm";
                        break;
                    case 0:
                        break;
                    default:
                        result += digits[units];
                        break;
                }
            }
        }
    }
    return result;
}

function convertNumberToVietnameseWords(number) {
    if (number == 0) return "Không đồng";
    if (number == "" || number == null) return "(Chưa nhập)";

    let result = "";
    let numberString = String(number);

    while (numberString.length % 3 != 0) {
        numberString = "0" + numberString;
    }

    let groupCount = numberString.length / 3;

    for (let i = 0; i < groupCount; i++) {
        let group = numberString.substring(i * 3, i * 3 + 3);
        let groupText = readThreeDigitGroup(group);

        if (groupText != "") {
            result += groupText + groups[groupCount - 1 - i];
        }
    }

    result = result.trim();
    if (result.startsWith("không trăm linh")) {
        result = result.substring(16);
    }
    if (result.startsWith("không trăm")) {
        result = result.substring(11);
    }

    return result.charAt(0).toUpperCase() + result.slice(1) + " đồng";
}

export default convertNumberToVietnameseWords;