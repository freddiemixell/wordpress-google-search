import BeatLoader from 'react-spinners/BeatLoader';
import styled from 'styled-components';

const ResultContainer = styled.ul`
  list-style: none;
  margin: 0;
  padding: 0;
`;

const ResultItem = styled.li`
  background: #f8f9fa;
  margin-bottom: 1%;
  padding: 1%;

  a {
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    transition: color .1s linear;
    background-color: transparent;
    cursor: pointer;
  }

  p {
    font-size: 16px;
    white-space: pre-line;
    word-wrap: break-word;
    margin: 0;
  }

  p.fm-search-highlight {
    color: ${props => props.color ? props.color : 'inherit'};
  }
`;

function SearchResults(props) {
  const results = props.results !== null
    ? props.results.items
    : [];

  const displayResults =  (typeof results !== 'undefined' && results.length > 0 ) ? results.map((result) => {
    const { title, link, displayLink, snippet } = result
    return (
      <ResultItem key={link} color='darkblue'>
        <a href={link}>{title}</a>
        <p className="fm-search-highlight">{displayLink}</p>
        <p>{snippet}</p>
      </ResultItem>
    );
  }) : null;

  if (displayResults === null) {
    return (
      <BeatLoader
        size={15}
      />
    );
  }

  return <ResultContainer>{ displayResults }</ResultContainer>
}

export default SearchResults;
