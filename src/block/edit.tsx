import {
  TextControl,
  PanelBody,
  SelectControl
} from "@wordpress/components";
import {
  InspectorControls,
  HeadingLevelDropdown,
  BlockControls,
  RichText
} from "@wordpress/block-editor"
import {
  useBlockProps,
} from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import { useEffect, useState } from "@wordpress/element";

interface EditProps {
  attributes: {
    width: "content-size" | "full-grid";
    headingLevel: 2 | 3 | 4 | 5 | 6;
    heading: string;
    searchTargetUrl: string;
    searchGetParameter: string;
  },
  setAttributes: (attributes: Partial<EditProps["attributes"]>) => void;
  blockProps: string[];
}

export default function Edit({attributes, setAttributes}: EditProps) {
  const {width, heading, headingLevel} = attributes;

  const [targetUrlDraft, setTargetUrlDraft] = useState(attributes.searchTargetUrl || "");
  const [getParamDraft, setGetParamDraft] = useState(attributes.searchGetParameter || "");

  useEffect(() => {
    setTargetUrlDraft(attributes.searchTargetUrl || "");
  }, [attributes.searchTargetUrl]);

  useEffect(() => {
    setGetParamDraft(attributes.searchGetParameter || "");
  }, [attributes.searchGetParameter]);

  const blockProps = useBlockProps({
    className: `fau-global-search-wrapper fau-global-search-wrapper--${width}`,
  });

  const HEADING_LEVELS = [2, 3, 4, 5, 6];
  const stripTags = (s: string) => s.replace(/<[^>]*>/g, '');
  const stripCtl = (s: string) => s.replace(/[\u0000-\u001F\u007F]/g, '');

  const sanitizeUrl = (raw: string): string => {
    const clean = stripCtl(stripTags(raw)).trim();
    if (!clean) return '';

    const wasAbsolute = /^https?:\/\//i.test(clean);
    try {
      const base = (typeof window !== 'undefined' && window.location?.origin) || 'https://example.local';
      const urlObj = new URL(clean, base);

      if (!/^https?:$/.test(urlObj.protocol)) return '';

      urlObj.username = '';
      urlObj.password = '';
      urlObj.search = '';
      urlObj.hash = '';

      return wasAbsolute ? `${urlObj.origin}${urlObj.pathname}` : urlObj.pathname;
    } catch {
      return '';
    }
  };

  const sanitizeGetParam = (raw: string): string => {
    const clean = stripCtl(stripTags(raw)).trim();
    if (!clean) return '';

    const withoutPrefix = clean.replace(/^[?&]+/, '');
    const firstChunk = withoutPrefix.split(/[&#]/)[0];
    const namePart = firstChunk.split('=')[0];

    const match = namePart.match(/^[A-Za-z][A-Za-z0-9._-]{0,63}$/);
    if (!match) return '';

    return `?${match[0]}=`;
  };

  const onChangeWidth = (newWidth: "content-size" | "full-grid") => {
    setAttributes({width: newWidth});
  };

  const onChangeHeadingLevel = (newHeadingLevel: 2 | 3 | 4 | 5 | 6) => {
    setAttributes({headingLevel: newHeadingLevel});
  };

  const onChangeHeading = (newHeading: string) => {
    setAttributes({heading: newHeading});
  };

  const onChangeSearchTargetUrl = (newSearchTargetUrl: string) => {
    setTargetUrlDraft(newSearchTargetUrl);
    setAttributes({ searchTargetUrl: newSearchTargetUrl });
  };

  const onBlurSearchTargetUrl = () => {
    const sanitized = sanitizeUrl(targetUrlDraft);
    setTargetUrlDraft(sanitized);
    setAttributes({ searchTargetUrl: sanitized });
  };

  const onChangeSearchGetParameter = (newSearchGetParameter: string) => {
    setGetParamDraft(newSearchGetParameter);
    setAttributes({ searchGetParameter: newSearchGetParameter });
  };

  const onBlurSearchGetParameter = () => {
    const sanitized = sanitizeGetParam(getParamDraft);
    setGetParamDraft(sanitized);
    setAttributes({ searchGetParameter: sanitized });
  };

  return (
    <>
      {heading.length > 0 && (
          <BlockControls group="block">
              <HeadingLevelDropdown
                  options={[2, 3, 4, 5, 6]}
                  value={headingLevel}
                  onChange={onChangeHeadingLevel}
              ></HeadingLevelDropdown>
          </BlockControls>
      )}
      <InspectorControls>
        <PanelBody title={__('Content Settings', 'rrze-search')}>
          <TextControl
            label={__('Heading', 'rrze-search')}
            value={heading}
            onChange={onChangeHeading}
            help={__(
              'Optional heading text to display above the search form',
              'rrze-search'
            )}
          />
        </PanelBody>
        {heading.length > 0 && (
        <PanelBody title={__('Layout Settings', 'rrze-search')}>
          <SelectControl
            label={__('Width', 'rrze-search')}
            value={width}
            options={[
              {
                label: __(
                  'Content Size (with search scope & advanced features)',
                  'rrze-search'
                ),
                value: 'content-size',
              },
              {
                label: __(
                  'Full Grid (simple search only)',
                  'rrze-search'
                ),
                value: 'full-grid',
              },
            ]}
            onChange={onChangeWidth}
            help={__(
              'Content Size: for editorial content, includes search scope selection and advanced features. Full Grid: for wide teaser components, simple search only.',
              'rrze-search'
            )}
          />
        </PanelBody>
      )}
      <PanelBody title={__('Advanced Search options', 'rrze-search' )} initialOpen={false} >
        <TextControl
          value={targetUrlDraft}
          onChange={onChangeSearchTargetUrl}
          onBlur={onBlurSearchTargetUrl}
          placeholder={"https://fau.de/page/"}
          label={__('Target URL for the Search Request.')}
        />
        <TextControl
          value={getParamDraft}
          onChange={onChangeSearchGetParameter}
          onBlur={onBlurSearchGetParameter}
          placeholder={"?search="}
          label={__('GET-Parameter for the Search Request.')}
        />
      </PanelBody>
      </InspectorControls>
      <div className="fau-global-search__outer-wrapper">
        <div {...blockProps}>
          {width === 'full-grid' && heading && (
            <RichText placeholder={__("Heading", "rrze-search")} tagName={`h${headingLevel}`} allowedFormats={[]} className="fau-global-search__heading fau-global-search__heading--full-grid" value={heading} onChange={onChangeHeading} />
          )}
          {width !== 'full-grid' && heading && (
            <RichText placeholder={__("Heading", "rrze-search")} tagName={`h${headingLevel}`} allowedFormats={[]} className="fau-global-search__heading" value={heading} onChange={onChangeHeading} />
          )}
          <form className="fau-global-search fau-global-search__form">
            <div
              className={`fau-global-search__input-wrapper${
                width === 'full-grid'
                  ? ' fau-global-search__input-wrapper--full-grid'
                  : ''
              }`}
            >
              <input
                type="search"
                className="fau-global-search__input"
                placeholder={__('Search…', 'rrze-search')}
                autoComplete="off"
                disabled
              />
              <button
                type="submit"
                className="fau-global-search__button"
                disabled
              >
								<span className="fau-global-search__button-text">
									{__('Search', 'rrze-search')}
								</span>
                <span
                  className="fau-global-search__button-icon"
                  aria-hidden="true"
                ></span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </>
  );
}
