/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/carrito/carrito-store.js":
/*!***********************************************!*\
  !*** ./resources/js/carrito/carrito-store.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"getCart\": () => (/* binding */ getCart),\n/* harmony export */   \"addToCart\": () => (/* binding */ addToCart)\n/* harmony export */ });\n// Cart Storage\nvar cartData = localStorage.getItem('cart');\nvar cart = cartData ? JSON.parse(cartData) : {\n  items: []\n}; //\n\nvar getCart = function getCart() {\n  var data = localStorage.getItem('cart');\n  return data ? JSON.parse(data) : {\n    items: []\n  };\n};\nfunction addToCart(productId, quantity) {\n  var isPlan = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;\n  // \n  var cartData = localStorage.getItem('cart');\n  var cart = cartData ? JSON.parse(cartData) : {\n    items: []\n  };\n  var existingItem = cart.items.find(function (item) {\n    return item.id === productId;\n  });\n  var currentQty = existingItem ? existingItem.quantity : 0;\n  var newQty = currentQty + quantity; // Validar en comparacion al stock disponibla\n  // Realizar consulta al backend para verificar que el stock sea el suficiente\n\n  var availableStock = 3;\n\n  if (newQty > availableStock) {\n    console.log(\"El monto de items que desea agregarse es mayor al stock existente\");\n    return {\n      success: false,\n      message: \"Solo hay \".concat(availableStock, \"u de \").concat(existingItem.nombre, \" disponibles\")\n    };\n  }\n\n  if (existingItem) {\n    existingItem.quantity = newQty;\n  } else {\n    cart.items.push({\n      id: productId,\n      quantity: quantity,\n      isPlan: isPlan\n    });\n  } // Save to localStorage\n\n\n  localStorage.setItem('cart', JSON.stringify(cart));\n  return {\n    success: true,\n    cart: cart\n  };\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvY2Fycml0by9jYXJyaXRvLXN0b3JlLmpzLmpzIiwibWFwcGluZ3MiOiI7Ozs7O0FBQUE7QUFDQSxJQUFNQSxRQUFRLEdBQUdDLFlBQVksQ0FBQ0MsT0FBYixDQUFxQixNQUFyQixDQUFqQjtBQUNBLElBQU1DLElBQUksR0FBR0gsUUFBUSxHQUFHSSxJQUFJLENBQUNDLEtBQUwsQ0FBV0wsUUFBWCxDQUFILEdBQTBCO0FBQUVNLEVBQUFBLEtBQUssRUFBRTtBQUFULENBQS9DLEVBRUE7O0FBRU8sSUFBTUMsT0FBTyxHQUFHLFNBQVZBLE9BQVUsR0FBTTtBQUMzQixNQUFNQyxJQUFJLEdBQUdQLFlBQVksQ0FBQ0MsT0FBYixDQUFxQixNQUFyQixDQUFiO0FBQ0EsU0FBT00sSUFBSSxHQUFHSixJQUFJLENBQUNDLEtBQUwsQ0FBV0csSUFBWCxDQUFILEdBQXNCO0FBQUNGLElBQUFBLEtBQUssRUFBQztBQUFQLEdBQWpDO0FBQ0QsQ0FITTtBQUtBLFNBQVNHLFNBQVQsQ0FBbUJDLFNBQW5CLEVBQThCQyxRQUE5QixFQUF3RDtBQUFBLE1BQWhCQyxNQUFnQix1RUFBUCxLQUFPO0FBQzNEO0FBQ0YsTUFBTVosUUFBUSxHQUFHQyxZQUFZLENBQUNDLE9BQWIsQ0FBcUIsTUFBckIsQ0FBakI7QUFDQSxNQUFNQyxJQUFJLEdBQUdILFFBQVEsR0FBR0ksSUFBSSxDQUFDQyxLQUFMLENBQVdMLFFBQVgsQ0FBSCxHQUEwQjtBQUFFTSxJQUFBQSxLQUFLLEVBQUU7QUFBVCxHQUEvQztBQUVBLE1BQU1PLFlBQVksR0FBR1YsSUFBSSxDQUFDRyxLQUFMLENBQVdRLElBQVgsQ0FBZ0IsVUFBQUMsSUFBSTtBQUFBLFdBQUlBLElBQUksQ0FBQ0MsRUFBTCxLQUFZTixTQUFoQjtBQUFBLEdBQXBCLENBQXJCO0FBQ0EsTUFBTU8sVUFBVSxHQUFHSixZQUFZLEdBQUdBLFlBQVksQ0FBQ0YsUUFBaEIsR0FBMkIsQ0FBMUQ7QUFDQSxNQUFNTyxNQUFNLEdBQUdELFVBQVUsR0FBR04sUUFBNUIsQ0FQNkQsQ0FTN0Q7QUFFQTs7QUFFQSxNQUFJUSxjQUFjLEdBQUcsQ0FBckI7O0FBRUEsTUFBSUQsTUFBTSxHQUFHQyxjQUFiLEVBQTZCO0FBQzNCQyxJQUFBQSxPQUFPLENBQUNDLEdBQVIsQ0FBWSxtRUFBWjtBQUNBLFdBQU87QUFBRUMsTUFBQUEsT0FBTyxFQUFFLEtBQVg7QUFBa0JDLE1BQUFBLE9BQU8scUJBQWNKLGNBQWQsa0JBQW9DTixZQUFZLENBQUNXLE1BQWpEO0FBQXpCLEtBQVA7QUFDRDs7QUFHRCxNQUFJWCxZQUFKLEVBQWtCO0FBQ2hCQSxJQUFBQSxZQUFZLENBQUNGLFFBQWIsR0FBd0JPLE1BQXhCO0FBQ0QsR0FGRCxNQUVPO0FBQ0xmLElBQUFBLElBQUksQ0FBQ0csS0FBTCxDQUFXbUIsSUFBWCxDQUFpQjtBQUFDVCxNQUFBQSxFQUFFLEVBQUVOLFNBQUw7QUFBZ0JDLE1BQUFBLFFBQVEsRUFBUkEsUUFBaEI7QUFBMEJDLE1BQUFBLE1BQU0sRUFBRUE7QUFBbEMsS0FBakI7QUFDRCxHQXpCNEQsQ0EyQjdEOzs7QUFDQVgsRUFBQUEsWUFBWSxDQUFDeUIsT0FBYixDQUFxQixNQUFyQixFQUE2QnRCLElBQUksQ0FBQ3VCLFNBQUwsQ0FBZXhCLElBQWYsQ0FBN0I7QUFFQSxTQUFPO0FBQUNtQixJQUFBQSxPQUFPLEVBQUUsSUFBVjtBQUFnQm5CLElBQUFBLElBQUksRUFBSkE7QUFBaEIsR0FBUDtBQUNEIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vLy4vcmVzb3VyY2VzL2pzL2NhcnJpdG8vY2Fycml0by1zdG9yZS5qcz9kNDgyIl0sInNvdXJjZXNDb250ZW50IjpbIi8vIENhcnQgU3RvcmFnZVxuY29uc3QgY2FydERhdGEgPSBsb2NhbFN0b3JhZ2UuZ2V0SXRlbSgnY2FydCcpO1xuY29uc3QgY2FydCA9IGNhcnREYXRhID8gSlNPTi5wYXJzZShjYXJ0RGF0YSkgOiB7IGl0ZW1zOiBbXSB9O1xuXG4vL1xuXG5leHBvcnQgY29uc3QgZ2V0Q2FydCA9ICgpID0+IHtcbiAgY29uc3QgZGF0YSA9IGxvY2FsU3RvcmFnZS5nZXRJdGVtKCdjYXJ0Jyk7XG4gIHJldHVybiBkYXRhID8gSlNPTi5wYXJzZShkYXRhKSA6IHtpdGVtczpbXX07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBhZGRUb0NhcnQocHJvZHVjdElkLCBxdWFudGl0eSwgaXNQbGFuID0gZmFsc2UpIHtcbiAgICAvLyBcbiAgY29uc3QgY2FydERhdGEgPSBsb2NhbFN0b3JhZ2UuZ2V0SXRlbSgnY2FydCcpO1xuICBjb25zdCBjYXJ0ID0gY2FydERhdGEgPyBKU09OLnBhcnNlKGNhcnREYXRhKSA6IHsgaXRlbXM6IFtdIH07XG4gIFxuICBjb25zdCBleGlzdGluZ0l0ZW0gPSBjYXJ0Lml0ZW1zLmZpbmQoaXRlbSA9PiBpdGVtLmlkID09PSBwcm9kdWN0SWQpO1xuICBjb25zdCBjdXJyZW50UXR5ID0gZXhpc3RpbmdJdGVtID8gZXhpc3RpbmdJdGVtLnF1YW50aXR5IDogMDtcbiAgY29uc3QgbmV3UXR5ID0gY3VycmVudFF0eSArIHF1YW50aXR5O1xuXG4gIC8vIFZhbGlkYXIgZW4gY29tcGFyYWNpb24gYWwgc3RvY2sgZGlzcG9uaWJsYVxuICBcbiAgLy8gUmVhbGl6YXIgY29uc3VsdGEgYWwgYmFja2VuZCBwYXJhIHZlcmlmaWNhciBxdWUgZWwgc3RvY2sgc2VhIGVsIHN1ZmljaWVudGVcblxuICBsZXQgYXZhaWxhYmxlU3RvY2sgPSAzO1xuXG4gIGlmIChuZXdRdHkgPiBhdmFpbGFibGVTdG9jaykge1xuICAgIGNvbnNvbGUubG9nKFwiRWwgbW9udG8gZGUgaXRlbXMgcXVlIGRlc2VhIGFncmVnYXJzZSBlcyBtYXlvciBhbCBzdG9jayBleGlzdGVudGVcIik7XG4gICAgcmV0dXJuIHsgc3VjY2VzczogZmFsc2UsIG1lc3NhZ2U6IGBTb2xvIGhheSAke2F2YWlsYWJsZVN0b2NrfXUgZGUgJHtleGlzdGluZ0l0ZW0ubm9tYnJlfSBkaXNwb25pYmxlc2B9XG4gIH1cblxuXG4gIGlmIChleGlzdGluZ0l0ZW0pIHtcbiAgICBleGlzdGluZ0l0ZW0ucXVhbnRpdHkgPSBuZXdRdHlcbiAgfSBlbHNlIHtcbiAgICBjYXJ0Lml0ZW1zLnB1c2goIHtpZDogcHJvZHVjdElkLCBxdWFudGl0eSwgaXNQbGFuOiBpc1BsYW59ICk7XG4gIH1cblxuICAvLyBTYXZlIHRvIGxvY2FsU3RvcmFnZVxuICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbSgnY2FydCcsIEpTT04uc3RyaW5naWZ5KGNhcnQpKTtcblxuICByZXR1cm4ge3N1Y2Nlc3M6IHRydWUsIGNhcnR9O1xufVxuXG4iXSwibmFtZXMiOlsiY2FydERhdGEiLCJsb2NhbFN0b3JhZ2UiLCJnZXRJdGVtIiwiY2FydCIsIkpTT04iLCJwYXJzZSIsIml0ZW1zIiwiZ2V0Q2FydCIsImRhdGEiLCJhZGRUb0NhcnQiLCJwcm9kdWN0SWQiLCJxdWFudGl0eSIsImlzUGxhbiIsImV4aXN0aW5nSXRlbSIsImZpbmQiLCJpdGVtIiwiaWQiLCJjdXJyZW50UXR5IiwibmV3UXR5IiwiYXZhaWxhYmxlU3RvY2siLCJjb25zb2xlIiwibG9nIiwic3VjY2VzcyIsIm1lc3NhZ2UiLCJub21icmUiLCJwdXNoIiwic2V0SXRlbSIsInN0cmluZ2lmeSJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/carrito/carrito-store.js\n");

/***/ }),

/***/ "./resources/js/carrito/index.js":
/*!***************************************!*\
  !*** ./resources/js/carrito/index.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _carrito_store_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./carrito-store.js */ \"./resources/js/carrito/carrito-store.js\");\n // import { getCartProductsInfo } from './carrito-service.js';\n// Make it available globally (only if needed)\n\nwindow.addToCart = _carrito_store_js__WEBPACK_IMPORTED_MODULE_0__.addToCart; // window.getCartProductsInfo = getCartProductsInfo;//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvY2Fycml0by9pbmRleC5qcy5qcyIsIm1hcHBpbmdzIjoiOztDQUNBO0FBRUE7O0FBQ0FDLE1BQU0sQ0FBQ0QsU0FBUCxHQUFtQkEsd0RBQW5CLEVBQ0EiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvY2Fycml0by9pbmRleC5qcz9kMzE4Il0sInNvdXJjZXNDb250ZW50IjpbImltcG9ydCB7IGFkZFRvQ2FydCB9IGZyb20gJy4vY2Fycml0by1zdG9yZS5qcyc7XG4vLyBpbXBvcnQgeyBnZXRDYXJ0UHJvZHVjdHNJbmZvIH0gZnJvbSAnLi9jYXJyaXRvLXNlcnZpY2UuanMnO1xuXG4vLyBNYWtlIGl0IGF2YWlsYWJsZSBnbG9iYWxseSAob25seSBpZiBuZWVkZWQpXG53aW5kb3cuYWRkVG9DYXJ0ID0gYWRkVG9DYXJ0O1xuLy8gd2luZG93LmdldENhcnRQcm9kdWN0c0luZm8gPSBnZXRDYXJ0UHJvZHVjdHNJbmZvOyJdLCJuYW1lcyI6WyJhZGRUb0NhcnQiLCJ3aW5kb3ciXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/carrito/index.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/js/carrito/index.js");
/******/ 	
/******/ })()
;