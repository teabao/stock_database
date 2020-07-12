let conditionNum = 0;
function newCondition() {
    let area = document.getElementById('conditionArea');
    let x =
        `<div class="condition">
                <input type="hidden" id="condition${conditionNum}" name="condition${conditionNum}" value="true">
                <select id="conditionVariable${conditionNum}" name="conditionVariable${conditionNum}">
                    <option value="open_price">開盤</option>
                    <option value="close_price">收盤</option>
                    <option value="high_price">高價</option>
                    <option value="low_price">低價</option>
                    <option value="volume">成交量</option>
                </select>
                <select id="conditionCompare${conditionNum}" name="conditionCompare${conditionNum}">
                    <option value=">">大於</option>
                    <option value=">=">大於等於</option>
                    <option value="<">小於</option>
                    <option value="<=">小於等於</option>
                    <option value="==">等於</option>
                    <option value="!=">不等於</option>
                </select>
                <input type="number" id="conditionValue${conditionNum}" name="conditionValue${conditionNum}" value="0">
            </div>`;
    area.innerHTML += x;
    conditionNum++;
}