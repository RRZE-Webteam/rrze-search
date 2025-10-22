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

/***/ "./src/block/block.json":
/*!******************************!*\
  !*** ./src/block/block.json ***!
  \******************************/
/***/ ((module) => {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"rrze-search/search","version":"1.1.2","title":"RRZE Search","category":"rrze","description":"Creates a blueprint block.","keywords":["search","global","suche"],"supports":{"html":false},"attributes":{"width":{"type":"string","default":"content-size","enum":["content-size","full-grid"]},"heading":{"type":"string","default":""},"headingLevel":{"type":"number","default":2,"enum":[2,3,4,5,6]},"searchTargetUrl":{"type":"string","default":""},"searchGetParameter":{"type":"string","default":""}},"textdomain":"rrze-elements-blocks","editorScript":"file:./index.ts","editorStyle":"file:./index.css","style":"file:./style-index.css"}');

/***/ }),

/***/ "./src/block/edit.tsx":
/*!****************************!*\
  !*** ./src/block/edit.tsx ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (/* binding */ Edit)\n/* harmony export */ });\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ \"@wordpress/components\");\n/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ \"@wordpress/block-editor\");\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ \"@wordpress/i18n\");\n/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);\n/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react/jsx-runtime */ \"react/jsx-runtime\");\n/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__);\n\n\n\n\n\nfunction Edit({\n  attributes,\n  setAttributes\n}) {\n  const {\n    width,\n    heading,\n    headingLevel\n  } = attributes;\n  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.useBlockProps)({\n    className: `fau-global-search-wrapper fau-global-search-wrapper--${width}`\n  });\n  const HEADING_LEVELS = [2, 3, 4, 5, 6];\n  const stripTags = s => s.replace(/<[^>]*>/g, '');\n  const stripCtl = s => s.replace(/[\\u0000-\\u001F\\u007F]/g, '');\n  const sanitizeUrl = raw => {\n    const clean = stripCtl(stripTags(raw)).trim();\n    if (!clean) return '';\n    const wasAbsolute = /^https?:\\/\\//i.test(clean);\n    try {\n      const base = typeof window !== 'undefined' && window.location?.origin || 'https://example.local';\n      const urlObj = new URL(clean, base);\n      if (!/^https?:$/.test(urlObj.protocol)) return '';\n      urlObj.username = '';\n      urlObj.password = '';\n      urlObj.search = '';\n      urlObj.hash = '';\n      return wasAbsolute ? `${urlObj.origin}${urlObj.pathname}` : urlObj.pathname;\n    } catch {\n      return '';\n    }\n  };\n  const sanitizeGetParam = raw => {\n    const clean = stripCtl(stripTags(raw)).trim();\n    if (!clean) return '';\n    const withoutPrefix = clean.replace(/^[?&]+/, '');\n    const firstChunk = withoutPrefix.split('&')[0];\n    const namePart = firstChunk.split('=')[0];\n    const match = namePart.match(/^[A-Za-z][A-Za-z0-9._-]{0,63}$/);\n    if (!match) return '';\n    return `?${match[0]}=`;\n  };\n  const onChangeWidth = newWidth => {\n    setAttributes({\n      width: newWidth\n    });\n  };\n  const onChangeHeadingLevel = newHeadingLevel => {\n    setAttributes({\n      headingLevel: newHeadingLevel\n    });\n  };\n  const onChangeHeading = newHeading => {\n    setAttributes({\n      heading: newHeading\n    });\n  };\n  const onChangeSearchTargetUrl = newSearchTargetUrl => {\n    const sanitized = sanitizeUrl(newSearchTargetUrl);\n    setAttributes({\n      searchTargetUrl: sanitized\n    });\n  };\n  const onChangeSearchGetParameter = newSearchGetParameter => {\n    const sanitized = sanitizeGetParam(newSearchGetParameter);\n    setAttributes({\n      searchGetParameter: sanitized\n    });\n  };\n  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.Fragment, {\n    children: [heading.length > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.BlockControls, {\n      group: \"block\",\n      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.HeadingLevelDropdown, {\n        options: [2, 3, 4, 5, 6],\n        value: headingLevel,\n        onChange: onChangeHeadingLevel\n      })\n    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {\n      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {\n        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Content Settings', 'rrze-search'),\n        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.TextControl, {\n          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Heading', 'rrze-search'),\n          value: heading,\n          onChange: onChangeHeading,\n          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Optional heading text to display above the search form', 'rrze-search')\n        })\n      }), heading.length > 0 && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {\n        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Layout Settings', 'rrze-search'),\n        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.SelectControl, {\n          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Width', 'rrze-search'),\n          value: width,\n          options: [{\n            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Content Size (with search scope & advanced features)', 'rrze-search'),\n            value: 'content-size'\n          }, {\n            label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Full Grid (simple search only)', 'rrze-search'),\n            value: 'full-grid'\n          }],\n          onChange: onChangeWidth,\n          help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Content Size: for editorial content, includes search scope selection and advanced features. Full Grid: for wide teaser components, simple search only.', 'rrze-search')\n        })\n      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.PanelBody, {\n        title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Advanced Search options', 'rrze-search'),\n        initialOpen: false,\n        children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.TextControl, {\n          value: attributes.searchTargetUrl,\n          onChange: onChangeSearchTargetUrl,\n          placeholder: \"https://fau.de/page/\",\n          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Target URL for the Search Request.')\n        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.TextControl, {\n          value: attributes.searchGetParameter,\n          onChange: onChangeSearchGetParameter,\n          placeholder: \"?search=\",\n          label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('GET-Parameter for the Search Request.')\n        })]\n      })]\n    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(\"div\", {\n      className: \"fau-global-search__outer-wrapper\",\n      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(\"div\", {\n        ...blockProps,\n        children: [width === 'full-grid' && heading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {\n          placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)(\"Heading\", \"rrze-search\"),\n          tagName: `h${headingLevel}`,\n          allowedFormats: [],\n          className: \"fau-global-search__heading fau-global-search__heading--full-grid\",\n          value: heading,\n          onChange: onChangeHeading\n        }), width !== 'full-grid' && heading && /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.RichText, {\n          placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)(\"Heading\", \"rrze-search\"),\n          tagName: `h${headingLevel}`,\n          allowedFormats: [],\n          className: \"fau-global-search__heading\",\n          value: heading,\n          onChange: onChangeHeading\n        }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(\"form\", {\n          className: \"fau-global-search fau-global-search__form\",\n          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(\"div\", {\n            className: `fau-global-search__input-wrapper${width === 'full-grid' ? ' fau-global-search__input-wrapper--full-grid' : ''}`,\n            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(\"input\", {\n              type: \"search\",\n              className: \"fau-global-search__input\",\n              placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Search…', 'rrze-search'),\n              autoComplete: \"off\",\n              disabled: true\n            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsxs)(\"button\", {\n              type: \"submit\",\n              className: \"fau-global-search__button\",\n              disabled: true,\n              children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(\"span\", {\n                className: \"fau-global-search__button-text\",\n                children: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Search', 'rrze-search')\n              }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_3__.jsx)(\"span\", {\n                className: \"fau-global-search__button-icon\",\n                \"aria-hidden\": \"true\"\n              })]\n            })]\n          })\n        })]\n      })\n    })]\n  });\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,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LENBQUUsQ0FDMUwsZUFDRDlDLHNEQUFBO1VBQU1ZLFNBQVMsRUFBQywyQ0FBMkM7VUFBQTJDLFFBQUEsZUFDekRyRCx1REFBQTtZQUNFVSxTQUFTLEVBQUUsbUNBQ1RKLEtBQUssS0FBSyxXQUFXLEdBQ2pCLDhDQUE4QyxHQUM5QyxFQUFFLEVBQ0w7WUFBQStDLFFBQUEsZ0JBRUh2RCxzREFBQTtjQUNFb0UsSUFBSSxFQUFDLFFBQVE7Y0FDYnhELFNBQVMsRUFBQywwQkFBMEI7Y0FDcENxRCxXQUFXLEVBQUVuRSxtREFBRSxDQUFDLFNBQVMsRUFBRSxhQUFhLENBQUU7Y0FDMUN1RSxZQUFZLEVBQUMsS0FBSztjQUNsQkMsUUFBUTtZQUFBLENBQ1QsQ0FBQyxlQUNGcEUsdURBQUE7Y0FDRWtFLElBQUksRUFBQyxRQUFRO2NBQ2J4RCxTQUFTLEVBQUMsMkJBQTJCO2NBQ3JDMEQsUUFBUTtjQUFBZixRQUFBLGdCQUVoQnZELHNEQUFBO2dCQUFNWSxTQUFTLEVBQUMsZ0NBQWdDO2dCQUFBMkMsUUFBQSxFQUM5Q3pELG1EQUFFLENBQUMsUUFBUSxFQUFFLGFBQWE7Y0FBQyxDQUN2QixDQUFDLGVBQ0NFLHNEQUFBO2dCQUNFWSxTQUFTLEVBQUMsZ0NBQWdDO2dCQUMxQyxlQUFZO2NBQU0sQ0FDYixDQUFDO1lBQUEsQ0FDRixDQUFDO1VBQUEsQ0FDTjtRQUFDLENBQ0YsQ0FBQztNQUFBLENBQ0o7SUFBQyxDQUNILENBQUM7RUFBQSxDQUNOLENBQUM7QUFFUCIsInNvdXJjZXMiOlsid2VicGFjazovL3JyemUtc2VhcmNoLy4vc3JjL2Jsb2NrL2VkaXQudHN4PzM3NWQiXSwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IHtcbiAgVGV4dENvbnRyb2wsXG4gIFBhbmVsQm9keSxcbiAgU2VsZWN0Q29udHJvbFxufSBmcm9tIFwiQHdvcmRwcmVzcy9jb21wb25lbnRzXCI7XG5pbXBvcnQge1xuICBJbnNwZWN0b3JDb250cm9scyxcbiAgSGVhZGluZ0xldmVsRHJvcGRvd24sXG4gIEJsb2NrQ29udHJvbHMsXG4gIFJpY2hUZXh0XG59IGZyb20gXCJAd29yZHByZXNzL2Jsb2NrLWVkaXRvclwiXG5pbXBvcnQge1xuICB1c2VCbG9ja1Byb3BzLFxufSBmcm9tIFwiQHdvcmRwcmVzcy9ibG9jay1lZGl0b3JcIjtcbmltcG9ydCB7X199IGZyb20gXCJAd29yZHByZXNzL2kxOG5cIjtcblxuaW50ZXJmYWNlIEVkaXRQcm9wcyB7XG4gIGF0dHJpYnV0ZXM6IHtcbiAgICB3aWR0aDogXCJjb250ZW50LXNpemVcIiB8IFwiZnVsbC1ncmlkXCI7XG4gICAgaGVhZGluZ0xldmVsOiAyIHwgMyB8IDQgfCA1IHwgNjtcbiAgICBoZWFkaW5nOiBzdHJpbmc7XG4gICAgc2VhcmNoVGFyZ2V0VXJsOiBzdHJpbmc7XG4gICAgc2VhcmNoR2V0UGFyYW1ldGVyOiBzdHJpbmc7XG4gIH0sXG4gIHNldEF0dHJpYnV0ZXM6IChhdHRyaWJ1dGVzOiBQYXJ0aWFsPEVkaXRQcm9wc1tcImF0dHJpYnV0ZXNcIl0+KSA9PiB2b2lkO1xuICBibG9ja1Byb3BzOiBzdHJpbmdbXTtcbn1cblxuZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gRWRpdCh7YXR0cmlidXRlcywgc2V0QXR0cmlidXRlc306IEVkaXRQcm9wcykge1xuICBjb25zdCB7d2lkdGgsIGhlYWRpbmcsIGhlYWRpbmdMZXZlbH0gPSBhdHRyaWJ1dGVzO1xuXG4gIGNvbnN0IGJsb2NrUHJvcHMgPSB1c2VCbG9ja1Byb3BzKHtcbiAgICBjbGFzc05hbWU6IGBmYXUtZ2xvYmFsLXNlYXJjaC13cmFwcGVyIGZhdS1nbG9iYWwtc2VhcmNoLXdyYXBwZXItLSR7d2lkdGh9YCxcbiAgfSk7XG5cbiAgY29uc3QgSEVBRElOR19MRVZFTFMgPSBbMiwgMywgNCwgNSwgNl07XG4gIGNvbnN0IHN0cmlwVGFncyA9IChzOiBzdHJpbmcpID0+IHMucmVwbGFjZSgvPFtePl0qPi9nLCAnJyk7XG4gIGNvbnN0IHN0cmlwQ3RsID0gKHM6IHN0cmluZykgPT4gcy5yZXBsYWNlKC9bXFx1MDAwMC1cXHUwMDFGXFx1MDA3Rl0vZywgJycpO1xuXG4gIGNvbnN0IHNhbml0aXplVXJsID0gKHJhdzogc3RyaW5nKTogc3RyaW5nID0+IHtcbiAgICBjb25zdCBjbGVhbiA9IHN0cmlwQ3RsKHN0cmlwVGFncyhyYXcpKS50cmltKCk7XG4gICAgaWYgKCFjbGVhbikgcmV0dXJuICcnO1xuXG4gICAgY29uc3Qgd2FzQWJzb2x1dGUgPSAvXmh0dHBzPzpcXC9cXC8vaS50ZXN0KGNsZWFuKTtcbiAgICB0cnkge1xuICAgICAgY29uc3QgYmFzZSA9ICh0eXBlb2Ygd2luZG93ICE9PSAndW5kZWZpbmVkJyAmJiB3aW5kb3cubG9jYXRpb24/Lm9yaWdpbikgfHwgJ2h0dHBzOi8vZXhhbXBsZS5sb2NhbCc7XG4gICAgICBjb25zdCB1cmxPYmogPSBuZXcgVVJMKGNsZWFuLCBiYXNlKTtcblxuICAgICAgaWYgKCEvXmh0dHBzPzokLy50ZXN0KHVybE9iai5wcm90b2NvbCkpIHJldHVybiAnJztcblxuICAgICAgdXJsT2JqLnVzZXJuYW1lID0gJyc7XG4gICAgICB1cmxPYmoucGFzc3dvcmQgPSAnJztcbiAgICAgIHVybE9iai5zZWFyY2ggPSAnJztcbiAgICAgIHVybE9iai5oYXNoID0gJyc7XG5cbiAgICAgIHJldHVybiB3YXNBYnNvbHV0ZSA/IGAke3VybE9iai5vcmlnaW59JHt1cmxPYmoucGF0aG5hbWV9YCA6IHVybE9iai5wYXRobmFtZTtcbiAgICB9IGNhdGNoIHtcbiAgICAgIHJldHVybiAnJztcbiAgICB9XG4gIH07XG5cbiAgY29uc3Qgc2FuaXRpemVHZXRQYXJhbSA9IChyYXc6IHN0cmluZyk6IHN0cmluZyA9PiB7XG4gICAgY29uc3QgY2xlYW4gPSBzdHJpcEN0bChzdHJpcFRhZ3MocmF3KSkudHJpbSgpO1xuICAgIGlmICghY2xlYW4pIHJldHVybiAnJztcblxuICAgIGNvbnN0IHdpdGhvdXRQcmVmaXggPSBjbGVhbi5yZXBsYWNlKC9eWz8mXSsvLCAnJyk7XG4gICAgY29uc3QgZmlyc3RDaHVuayA9IHdpdGhvdXRQcmVmaXguc3BsaXQoJyYnKVswXTtcbiAgICBjb25zdCBuYW1lUGFydCA9IGZpcnN0Q2h1bmsuc3BsaXQoJz0nKVswXTtcblxuICAgIGNvbnN0IG1hdGNoID0gbmFtZVBhcnQubWF0Y2goL15bQS1aYS16XVtBLVphLXowLTkuXy1dezAsNjN9JC8pO1xuICAgIGlmICghbWF0Y2gpIHJldHVybiAnJztcblxuICAgIHJldHVybiBgPyR7bWF0Y2hbMF19PWA7XG4gIH07XG5cbiAgY29uc3Qgb25DaGFuZ2VXaWR0aCA9IChuZXdXaWR0aDogXCJjb250ZW50LXNpemVcIiB8IFwiZnVsbC1ncmlkXCIpID0+IHtcbiAgICBzZXRBdHRyaWJ1dGVzKHt3aWR0aDogbmV3V2lkdGh9KTtcbiAgfTtcblxuICBjb25zdCBvbkNoYW5nZUhlYWRpbmdMZXZlbCA9IChuZXdIZWFkaW5nTGV2ZWw6IDIgfCAzIHwgNCB8IDUgfCA2KSA9PiB7XG4gICAgc2V0QXR0cmlidXRlcyh7aGVhZGluZ0xldmVsOiBuZXdIZWFkaW5nTGV2ZWx9KTtcbiAgfTtcblxuICBjb25zdCBvbkNoYW5nZUhlYWRpbmcgPSAobmV3SGVhZGluZzogc3RyaW5nKSA9PiB7XG4gICAgc2V0QXR0cmlidXRlcyh7aGVhZGluZzogbmV3SGVhZGluZ30pO1xuICB9O1xuXG4gIGNvbnN0IG9uQ2hhbmdlU2VhcmNoVGFyZ2V0VXJsID0gKG5ld1NlYXJjaFRhcmdldFVybDogc3RyaW5nKSA9PiB7XG4gICAgY29uc3Qgc2FuaXRpemVkID0gc2FuaXRpemVVcmwobmV3U2VhcmNoVGFyZ2V0VXJsKTtcbiAgICBzZXRBdHRyaWJ1dGVzKHsgc2VhcmNoVGFyZ2V0VXJsOiBzYW5pdGl6ZWQgfSk7XG4gIH07XG5cbiAgY29uc3Qgb25DaGFuZ2VTZWFyY2hHZXRQYXJhbWV0ZXIgPSAobmV3U2VhcmNoR2V0UGFyYW1ldGVyOiBzdHJpbmcpID0+IHtcbiAgICBjb25zdCBzYW5pdGl6ZWQgPSBzYW5pdGl6ZUdldFBhcmFtKG5ld1NlYXJjaEdldFBhcmFtZXRlcik7XG4gICAgc2V0QXR0cmlidXRlcyh7IHNlYXJjaEdldFBhcmFtZXRlcjogc2FuaXRpemVkIH0pO1xuICB9O1xuXG4gIHJldHVybiAoXG4gICAgPD5cbiAgICAgIHtoZWFkaW5nLmxlbmd0aCA+IDAgJiYgKFxuICAgICAgICAgIDxCbG9ja0NvbnRyb2xzIGdyb3VwPVwiYmxvY2tcIj5cbiAgICAgICAgICAgICAgPEhlYWRpbmdMZXZlbERyb3Bkb3duXG4gICAgICAgICAgICAgICAgICBvcHRpb25zPXtbMiwgMywgNCwgNSwgNl19XG4gICAgICAgICAgICAgICAgICB2YWx1ZT17aGVhZGluZ0xldmVsfVxuICAgICAgICAgICAgICAgICAgb25DaGFuZ2U9e29uQ2hhbmdlSGVhZGluZ0xldmVsfVxuICAgICAgICAgICAgICA+PC9IZWFkaW5nTGV2ZWxEcm9wZG93bj5cbiAgICAgICAgICA8L0Jsb2NrQ29udHJvbHM+XG4gICAgICApfVxuICAgICAgPEluc3BlY3RvckNvbnRyb2xzPlxuICAgICAgICA8UGFuZWxCb2R5IHRpdGxlPXtfXygnQ29udGVudCBTZXR0aW5ncycsICdycnplLXNlYXJjaCcpfT5cbiAgICAgICAgICA8VGV4dENvbnRyb2xcbiAgICAgICAgICAgIGxhYmVsPXtfXygnSGVhZGluZycsICdycnplLXNlYXJjaCcpfVxuICAgICAgICAgICAgdmFsdWU9e2hlYWRpbmd9XG4gICAgICAgICAgICBvbkNoYW5nZT17b25DaGFuZ2VIZWFkaW5nfVxuICAgICAgICAgICAgaGVscD17X18oXG4gICAgICAgICAgICAgICdPcHRpb25hbCBoZWFkaW5nIHRleHQgdG8gZGlzcGxheSBhYm92ZSB0aGUgc2VhcmNoIGZvcm0nLFxuICAgICAgICAgICAgICAncnJ6ZS1zZWFyY2gnXG4gICAgICAgICAgICApfVxuICAgICAgICAgIC8+XG4gICAgICAgIDwvUGFuZWxCb2R5PlxuICAgICAgICB7aGVhZGluZy5sZW5ndGggPiAwICYmIChcbiAgICAgICAgPFBhbmVsQm9keSB0aXRsZT17X18oJ0xheW91dCBTZXR0aW5ncycsICdycnplLXNlYXJjaCcpfT5cbiAgICAgICAgICA8U2VsZWN0Q29udHJvbFxuICAgICAgICAgICAgbGFiZWw9e19fKCdXaWR0aCcsICdycnplLXNlYXJjaCcpfVxuICAgICAgICAgICAgdmFsdWU9e3dpZHRofVxuICAgICAgICAgICAgb3B0aW9ucz17W1xuICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgbGFiZWw6IF9fKFxuICAgICAgICAgICAgICAgICAgJ0NvbnRlbnQgU2l6ZSAod2l0aCBzZWFyY2ggc2NvcGUgJiBhZHZhbmNlZCBmZWF0dXJlcyknLFxuICAgICAgICAgICAgICAgICAgJ3JyemUtc2VhcmNoJ1xuICAgICAgICAgICAgICAgICksXG4gICAgICAgICAgICAgICAgdmFsdWU6ICdjb250ZW50LXNpemUnLFxuICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgbGFiZWw6IF9fKFxuICAgICAgICAgICAgICAgICAgJ0Z1bGwgR3JpZCAoc2ltcGxlIHNlYXJjaCBvbmx5KScsXG4gICAgICAgICAgICAgICAgICAncnJ6ZS1zZWFyY2gnXG4gICAgICAgICAgICAgICAgKSxcbiAgICAgICAgICAgICAgICB2YWx1ZTogJ2Z1bGwtZ3JpZCcsXG4gICAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBdfVxuICAgICAgICAgICAgb25DaGFuZ2U9e29uQ2hhbmdlV2lkdGh9XG4gICAgICAgICAgICBoZWxwPXtfXyhcbiAgICAgICAgICAgICAgJ0NvbnRlbnQgU2l6ZTogZm9yIGVkaXRvcmlhbCBjb250ZW50LCBpbmNsdWRlcyBzZWFyY2ggc2NvcGUgc2VsZWN0aW9uIGFuZCBhZHZhbmNlZCBmZWF0dXJlcy4gRnVsbCBHcmlkOiBmb3Igd2lkZSB0ZWFzZXIgY29tcG9uZW50cywgc2ltcGxlIHNlYXJjaCBvbmx5LicsXG4gICAgICAgICAgICAgICdycnplLXNlYXJjaCdcbiAgICAgICAgICAgICl9XG4gICAgICAgICAgLz5cbiAgICAgICAgPC9QYW5lbEJvZHk+XG4gICAgICApfVxuICAgICAgPFBhbmVsQm9keSB0aXRsZT17X18oJ0FkdmFuY2VkIFNlYXJjaCBvcHRpb25zJywgJ3JyemUtc2VhcmNoJyApfSBpbml0aWFsT3Blbj17ZmFsc2V9ID5cbiAgICAgICAgPFRleHRDb250cm9sIHZhbHVlPXthdHRyaWJ1dGVzLnNlYXJjaFRhcmdldFVybH0gb25DaGFuZ2U9e29uQ2hhbmdlU2VhcmNoVGFyZ2V0VXJsfSBwbGFjZWhvbGRlcj17XCJodHRwczovL2ZhdS5kZS9wYWdlL1wifSBsYWJlbD17X18oJ1RhcmdldCBVUkwgZm9yIHRoZSBTZWFyY2ggUmVxdWVzdC4nKX0gLz5cbiAgICAgICAgPFRleHRDb250cm9sIHZhbHVlPXthdHRyaWJ1dGVzLnNlYXJjaEdldFBhcmFtZXRlcn0gb25DaGFuZ2U9e29uQ2hhbmdlU2VhcmNoR2V0UGFyYW1ldGVyfSBwbGFjZWhvbGRlcj17XCI/c2VhcmNoPVwifSBsYWJlbD17X18oJ0dFVC1QYXJhbWV0ZXIgZm9yIHRoZSBTZWFyY2ggUmVxdWVzdC4nKX0gLz5cbiAgICAgIDwvUGFuZWxCb2R5PlxuICAgICAgPC9JbnNwZWN0b3JDb250cm9scz5cbiAgICAgIDxkaXYgY2xhc3NOYW1lPVwiZmF1LWdsb2JhbC1zZWFyY2hfX291dGVyLXdyYXBwZXJcIj5cbiAgICAgICAgPGRpdiB7Li4uYmxvY2tQcm9wc30+XG4gICAgICAgICAge3dpZHRoID09PSAnZnVsbC1ncmlkJyAmJiBoZWFkaW5nICYmIChcbiAgICAgICAgICAgIDxSaWNoVGV4dCBwbGFjZWhvbGRlcj17X18oXCJIZWFkaW5nXCIsIFwicnJ6ZS1zZWFyY2hcIil9IHRhZ05hbWU9e2BoJHtoZWFkaW5nTGV2ZWx9YH0gYWxsb3dlZEZvcm1hdHM9e1tdfSBjbGFzc05hbWU9XCJmYXUtZ2xvYmFsLXNlYXJjaF9faGVhZGluZyBmYXUtZ2xvYmFsLXNlYXJjaF9faGVhZGluZy0tZnVsbC1ncmlkXCIgdmFsdWU9e2hlYWRpbmd9IG9uQ2hhbmdlPXtvbkNoYW5nZUhlYWRpbmd9IC8+XG4gICAgICAgICAgKX1cbiAgICAgICAgICB7d2lkdGggIT09ICdmdWxsLWdyaWQnICYmIGhlYWRpbmcgJiYgKFxuICAgICAgICAgICAgPFJpY2hUZXh0IHBsYWNlaG9sZGVyPXtfXyhcIkhlYWRpbmdcIiwgXCJycnplLXNlYXJjaFwiKX0gdGFnTmFtZT17YGgke2hlYWRpbmdMZXZlbH1gfSBhbGxvd2VkRm9ybWF0cz17W119IGNsYXNzTmFtZT1cImZhdS1nbG9iYWwtc2VhcmNoX19oZWFkaW5nXCIgdmFsdWU9e2hlYWRpbmd9IG9uQ2hhbmdlPXtvbkNoYW5nZUhlYWRpbmd9IC8+XG4gICAgICAgICAgKX1cbiAgICAgICAgICA8Zm9ybSBjbGFzc05hbWU9XCJmYXUtZ2xvYmFsLXNlYXJjaCBmYXUtZ2xvYmFsLXNlYXJjaF9fZm9ybVwiPlxuICAgICAgICAgICAgPGRpdlxuICAgICAgICAgICAgICBjbGFzc05hbWU9e2BmYXUtZ2xvYmFsLXNlYXJjaF9faW5wdXQtd3JhcHBlciR7XG4gICAgICAgICAgICAgICAgd2lkdGggPT09ICdmdWxsLWdyaWQnXG4gICAgICAgICAgICAgICAgICA/ICcgZmF1LWdsb2JhbC1zZWFyY2hfX2lucHV0LXdyYXBwZXItLWZ1bGwtZ3JpZCdcbiAgICAgICAgICAgICAgICAgIDogJydcbiAgICAgICAgICAgICAgfWB9XG4gICAgICAgICAgICA+XG4gICAgICAgICAgICAgIDxpbnB1dFxuICAgICAgICAgICAgICAgIHR5cGU9XCJzZWFyY2hcIlxuICAgICAgICAgICAgICAgIGNsYXNzTmFtZT1cImZhdS1nbG9iYWwtc2VhcmNoX19pbnB1dFwiXG4gICAgICAgICAgICAgICAgcGxhY2Vob2xkZXI9e19fKCdTZWFyY2jigKYnLCAncnJ6ZS1zZWFyY2gnKX1cbiAgICAgICAgICAgICAgICBhdXRvQ29tcGxldGU9XCJvZmZcIlxuICAgICAgICAgICAgICAgIGRpc2FibGVkXG4gICAgICAgICAgICAgIC8+XG4gICAgICAgICAgICAgIDxidXR0b25cbiAgICAgICAgICAgICAgICB0eXBlPVwic3VibWl0XCJcbiAgICAgICAgICAgICAgICBjbGFzc05hbWU9XCJmYXUtZ2xvYmFsLXNlYXJjaF9fYnV0dG9uXCJcbiAgICAgICAgICAgICAgICBkaXNhYmxlZFxuICAgICAgICAgICAgICA+XG5cdFx0XHRcdFx0XHRcdFx0PHNwYW4gY2xhc3NOYW1lPVwiZmF1LWdsb2JhbC1zZWFyY2hfX2J1dHRvbi10ZXh0XCI+XG5cdFx0XHRcdFx0XHRcdFx0XHR7X18oJ1NlYXJjaCcsICdycnplLXNlYXJjaCcpfVxuXHRcdFx0XHRcdFx0XHRcdDwvc3Bhbj5cbiAgICAgICAgICAgICAgICA8c3BhblxuICAgICAgICAgICAgICAgICAgY2xhc3NOYW1lPVwiZmF1LWdsb2JhbC1zZWFyY2hfX2J1dHRvbi1pY29uXCJcbiAgICAgICAgICAgICAgICAgIGFyaWEtaGlkZGVuPVwidHJ1ZVwiXG4gICAgICAgICAgICAgICAgPjwvc3Bhbj5cbiAgICAgICAgICAgICAgPC9idXR0b24+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Zvcm0+XG4gICAgICAgIDwvZGl2PlxuICAgICAgPC9kaXY+XG4gICAgPC8+XG4gICk7XG59XG4iXSwibmFtZXMiOlsiVGV4dENvbnRyb2wiLCJQYW5lbEJvZHkiLCJTZWxlY3RDb250cm9sIiwiSW5zcGVjdG9yQ29udHJvbHMiLCJIZWFkaW5nTGV2ZWxEcm9wZG93biIsIkJsb2NrQ29udHJvbHMiLCJSaWNoVGV4dCIsInVzZUJsb2NrUHJvcHMiLCJfXyIsImpzeCIsIl9qc3giLCJqc3hzIiwiX2pzeHMiLCJGcmFnbWVudCIsIl9GcmFnbWVudCIsIkVkaXQiLCJhdHRyaWJ1dGVzIiwic2V0QXR0cmlidXRlcyIsIndpZHRoIiwiaGVhZGluZyIsImhlYWRpbmdMZXZlbCIsImJsb2NrUHJvcHMiLCJjbGFzc05hbWUiLCJIRUFESU5HX0xFVkVMUyIsInN0cmlwVGFncyIsInMiLCJyZXBsYWNlIiwic3RyaXBDdGwiLCJzYW5pdGl6ZVVybCIsInJhdyIsImNsZWFuIiwidHJpbSIsIndhc0Fic29sdXRlIiwidGVzdCIsImJhc2UiLCJ3aW5kb3ciLCJsb2NhdGlvbiIsIm9yaWdpbiIsInVybE9iaiIsIlVSTCIsInByb3RvY29sIiwidXNlcm5hbWUiLCJwYXNzd29yZCIsInNlYXJjaCIsImhhc2giLCJwYXRobmFtZSIsInNhbml0aXplR2V0UGFyYW0iLCJ3aXRob3V0UHJlZml4IiwiZmlyc3RDaHVuayIsInNwbGl0IiwibmFtZVBhcnQiLCJtYXRjaCIsIm9uQ2hhbmdlV2lkdGgiLCJuZXdXaWR0aCIsIm9uQ2hhbmdlSGVhZGluZ0xldmVsIiwibmV3SGVhZGluZ0xldmVsIiwib25DaGFuZ2VIZWFkaW5nIiwibmV3SGVhZGluZyIsIm9uQ2hhbmdlU2VhcmNoVGFyZ2V0VXJsIiwibmV3U2VhcmNoVGFyZ2V0VXJsIiwic2FuaXRpemVkIiwic2VhcmNoVGFyZ2V0VXJsIiwib25DaGFuZ2VTZWFyY2hHZXRQYXJhbWV0ZXIiLCJuZXdTZWFyY2hHZXRQYXJhbWV0ZXIiLCJzZWFyY2hHZXRQYXJhbWV0ZXIiLCJjaGlsZHJlbiIsImxlbmd0aCIsImdyb3VwIiwib3B0aW9ucyIsInZhbHVlIiwib25DaGFuZ2UiLCJ0aXRsZSIsImxhYmVsIiwiaGVscCIsImluaXRpYWxPcGVuIiwicGxhY2Vob2xkZXIiLCJ0YWdOYW1lIiwiYWxsb3dlZEZvcm1hdHMiLCJ0eXBlIiwiYXV0b0NvbXBsZXRlIiwiZGlzYWJsZWQiXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./src/block/edit.tsx\n\n}");

/***/ }),

/***/ "./src/block/editor.scss":
/*!*******************************!*\
  !*** ./src/block/editor.scss ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("{__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9zcmMvYmxvY2svZWRpdG9yLnNjc3MiLCJtYXBwaW5ncyI6IjtBQUFBIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vcnJ6ZS1zZWFyY2gvLi9zcmMvYmxvY2svZWRpdG9yLnNjc3M/YzBkOCJdLCJzb3VyY2VzQ29udGVudCI6WyIvLyBleHRyYWN0ZWQgYnkgbWluaS1jc3MtZXh0cmFjdC1wbHVnaW5cbmV4cG9ydCB7fTsiXSwibmFtZXMiOltdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./src/block/editor.scss\n\n}");

/***/ }),

/***/ "./src/block/index.tsx":
/*!*****************************!*\
  !*** ./src/block/index.tsx ***!
  \*****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ \"@wordpress/blocks\");\n/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ \"./src/block/edit.tsx\");\n/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ \"./src/block/block.json\");\n/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor.scss */ \"./src/block/editor.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ \"./src/block/style.scss\");\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/block-editor */ \"@wordpress/block-editor\");\n/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__);\n/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ \"react/jsx-runtime\");\n/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__);\n\n\n\n\n\n\n\n(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_2__.name, {\n  icon: {\n    src: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsxs)(\"svg\", {\n      id: \"Ebene_1\",\n      xmlns: \"http://www.w3.org/2000/svg\",\n      viewBox: \"0 0 512 512\",\n      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(\"rect\", {\n        x: \"60.05\",\n        y: \"115.69\",\n        width: \"112.94\",\n        height: \"280.62\",\n        rx: \"5.73\",\n        ry: \"5.73\",\n        fill: \"evenodd\",\n        strokeWidth: \"0\"\n      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(\"rect\", {\n        x: \"199.53\",\n        y: \"115.69\",\n        width: \"112.94\",\n        height: \"280.62\",\n        rx: \"5.73\",\n        ry: \"5.73\",\n        fill: \"evenodd\",\n        strokeWidth: \"0\"\n      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(\"rect\", {\n        x: \"339.01\",\n        y: \"115.69\",\n        width: \"112.94\",\n        height: \"280.62\",\n        rx: \"5.73\",\n        ry: \"5.73\",\n        fill: \"evenodd\",\n        strokeWidth: \"0\"\n      })]\n    })\n  },\n  __experimentalLabel: (attributes, {\n    context\n  }) => {\n    const {\n      title\n    } = attributes;\n    if (context === 'list-view' && title) {\n      return title;\n    }\n  },\n  // @see ./edit.js\n  edit: _edit__WEBPACK_IMPORTED_MODULE_1__[\"default\"],\n  // @see ./save.js\n  save: () => /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__.InnerBlocks.Content, {})\n});//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9zcmMvYmxvY2svaW5kZXgudHN4IiwibWFwcGluZ3MiOiI7Ozs7Ozs7Ozs7O0FBQXNEO0FBRTVCO0FBQ1U7QUFDYjtBQUNEO0FBQ2dDO0FBQUE7QUFFdERBLG9FQUFpQixDQUFFRSw2Q0FBYSxFQUFTO0VBQ3hDTyxJQUFJLEVBQUU7SUFDTEMsR0FBRyxlQUFFSCx1REFBQTtNQUFLSSxFQUFFLEVBQUMsU0FBUztNQUFDQyxLQUFLLEVBQUMsNEJBQTRCO01BQUNDLE9BQU8sRUFBQyxhQUFhO01BQUFDLFFBQUEsZ0JBQUNULHNEQUFBO1FBQU1VLENBQUMsRUFBQyxPQUFPO1FBQUNDLENBQUMsRUFBQyxRQUFRO1FBQUNDLEtBQUssRUFBQyxRQUFRO1FBQUNDLE1BQU0sRUFBQyxRQUFRO1FBQUNDLEVBQUUsRUFBQyxNQUFNO1FBQUNDLEVBQUUsRUFBQyxNQUFNO1FBQUNDLElBQUksRUFBQyxTQUFTO1FBQUNDLFdBQVcsRUFBQztNQUFHLENBQUMsQ0FBQyxlQUFBakIsc0RBQUE7UUFBTVUsQ0FBQyxFQUFDLFFBQVE7UUFBQ0MsQ0FBQyxFQUFDLFFBQVE7UUFBQ0MsS0FBSyxFQUFDLFFBQVE7UUFBQ0MsTUFBTSxFQUFDLFFBQVE7UUFBQ0MsRUFBRSxFQUFDLE1BQU07UUFBQ0MsRUFBRSxFQUFDLE1BQU07UUFBQ0MsSUFBSSxFQUFDLFNBQVM7UUFBQ0MsV0FBVyxFQUFDO01BQUcsQ0FBQyxDQUFDLGVBQUFqQixzREFBQTtRQUFNVSxDQUFDLEVBQUMsUUFBUTtRQUFDQyxDQUFDLEVBQUMsUUFBUTtRQUFDQyxLQUFLLEVBQUMsUUFBUTtRQUFDQyxNQUFNLEVBQUMsUUFBUTtRQUFDQyxFQUFFLEVBQUMsTUFBTTtRQUFDQyxFQUFFLEVBQUMsTUFBTTtRQUFDQyxJQUFJLEVBQUMsU0FBUztRQUFDQyxXQUFXLEVBQUM7TUFBRyxDQUFDLENBQUM7SUFBQSxDQUFLO0VBQ2xhLENBQUM7RUFDREMsbUJBQW1CLEVBQUVBLENBQUNDLFVBQWUsRUFBRTtJQUFFQztFQUFhLENBQUMsS0FBSztJQUMzRCxNQUFNO01BQUVDO0lBQU0sQ0FBQyxHQUFHRixVQUFVO0lBRTVCLElBQUlDLE9BQU8sS0FBSyxXQUFXLElBQUlDLEtBQUssRUFBRTtNQUNyQyxPQUFPQSxLQUFLO0lBQ2I7RUFDRCxDQUFDO0VBQ0Q7RUFDQUMsSUFBSSxFQUFFMUIsNkNBQUk7RUFFVjtFQUNBMkIsSUFBSSxFQUFFQSxDQUFBLGtCQUFNdkIsc0RBQUEsQ0FBQ0YsZ0VBQVcsQ0FBQzBCLE9BQU8sSUFBRTtBQUNuQyxDQUFTLENBQUMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9ycnplLXNlYXJjaC8uL3NyYy9ibG9jay9pbmRleC50c3g/MGFiNiJdLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgeyByZWdpc3RlckJsb2NrVHlwZSB9IGZyb20gJ0B3b3JkcHJlc3MvYmxvY2tzJztcblxuaW1wb3J0IEVkaXQgZnJvbSAnLi9lZGl0JztcbmltcG9ydCBtZXRhZGF0YSBmcm9tICcuL2Jsb2NrLmpzb24nO1xuaW1wb3J0ICcuL2VkaXRvci5zY3NzJztcbmltcG9ydCAnLi9zdHlsZS5zY3NzJztcbmltcG9ydCB7IElubmVyQmxvY2tzIH0gZnJvbSAnQHdvcmRwcmVzcy9ibG9jay1lZGl0b3InO1xuXG5yZWdpc3RlckJsb2NrVHlwZSggbWV0YWRhdGEubmFtZSBhcyBhbnksIHtcblx0aWNvbjoge1xuXHRcdHNyYzogPHN2ZyBpZD1cIkViZW5lXzFcIiB4bWxucz1cImh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnXCIgdmlld0JveD1cIjAgMCA1MTIgNTEyXCI+PHJlY3QgeD1cIjYwLjA1XCIgeT1cIjExNS42OVwiIHdpZHRoPVwiMTEyLjk0XCIgaGVpZ2h0PVwiMjgwLjYyXCIgcng9XCI1LjczXCIgcnk9XCI1LjczXCIgZmlsbD1cImV2ZW5vZGRcIiBzdHJva2VXaWR0aD1cIjBcIi8+PHJlY3QgeD1cIjE5OS41M1wiIHk9XCIxMTUuNjlcIiB3aWR0aD1cIjExMi45NFwiIGhlaWdodD1cIjI4MC42MlwiIHJ4PVwiNS43M1wiIHJ5PVwiNS43M1wiIGZpbGw9XCJldmVub2RkXCIgc3Ryb2tlV2lkdGg9XCIwXCIvPjxyZWN0IHg9XCIzMzkuMDFcIiB5PVwiMTE1LjY5XCIgd2lkdGg9XCIxMTIuOTRcIiBoZWlnaHQ9XCIyODAuNjJcIiByeD1cIjUuNzNcIiByeT1cIjUuNzNcIiBmaWxsPVwiZXZlbm9kZFwiIHN0cm9rZVdpZHRoPVwiMFwiLz48L3N2Zz5cblx0fSxcblx0X19leHBlcmltZW50YWxMYWJlbDogKGF0dHJpYnV0ZXM6IGFueSwgeyBjb250ZXh0IH06IGFueSkgPT4ge1xuXHRcdGNvbnN0IHsgdGl0bGUgfSA9IGF0dHJpYnV0ZXM7XG5cblx0XHRpZiAoY29udGV4dCA9PT0gJ2xpc3QtdmlldycgJiYgdGl0bGUpIHtcblx0XHRcdHJldHVybiB0aXRsZTtcblx0XHR9XG5cdH0sXG5cdC8vIEBzZWUgLi9lZGl0LmpzXG5cdGVkaXQ6IEVkaXQsXG5cblx0Ly8gQHNlZSAuL3NhdmUuanNcblx0c2F2ZTogKCkgPT4gPElubmVyQmxvY2tzLkNvbnRlbnQgLz4sXG59IGFzIGFueSApO1xuIl0sIm5hbWVzIjpbInJlZ2lzdGVyQmxvY2tUeXBlIiwiRWRpdCIsIm1ldGFkYXRhIiwiSW5uZXJCbG9ja3MiLCJqc3giLCJfanN4IiwianN4cyIsIl9qc3hzIiwibmFtZSIsImljb24iLCJzcmMiLCJpZCIsInhtbG5zIiwidmlld0JveCIsImNoaWxkcmVuIiwieCIsInkiLCJ3aWR0aCIsImhlaWdodCIsInJ4IiwicnkiLCJmaWxsIiwic3Ryb2tlV2lkdGgiLCJfX2V4cGVyaW1lbnRhbExhYmVsIiwiYXR0cmlidXRlcyIsImNvbnRleHQiLCJ0aXRsZSIsImVkaXQiLCJzYXZlIiwiQ29udGVudCJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./src/block/index.tsx\n\n}");

/***/ }),

/***/ "./src/block/style.scss":
/*!******************************!*\
  !*** ./src/block/style.scss ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("{__webpack_require__.r(__webpack_exports__);\n// extracted by mini-css-extract-plugin\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9zcmMvYmxvY2svc3R5bGUuc2NzcyIsIm1hcHBpbmdzIjoiO0FBQUEiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9ycnplLXNlYXJjaC8uL3NyYy9ibG9jay9zdHlsZS5zY3NzP2E1NTgiXSwic291cmNlc0NvbnRlbnQiOlsiLy8gZXh0cmFjdGVkIGJ5IG1pbmktY3NzLWV4dHJhY3QtcGx1Z2luXG5leHBvcnQge307Il0sIm5hbWVzIjpbXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./src/block/style.scss\n\n}");

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"block/index": 0,
/******/ 			"block/style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkrrze_search"] = self["webpackChunkrrze_search"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["block/style-index"], () => (__webpack_require__("./src/block/index.tsx")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;