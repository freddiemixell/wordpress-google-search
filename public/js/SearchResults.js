import BeatLoader from 'react-spinners/BeatLoader';
import styled from 'styled-components';

const Loader = styled.div`
  display: flex !important;
  justify-content: center;
  align-items: center;
  height: 50vh;
`;

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

  const { error, loading } = props;

  const results = props.results !== null
    ? props.results.items
    : [];

  const displayResults =  (typeof results !== 'undefined' && results.length > 0 ) ? results.map((result) => {
    const { title, link, snippet } = result
    return (
      <ResultItem key={link} color='darkblue'>
        <a href={link}>{title}</a>
        <p className="fm-search-highlight">{link}</p>
        <p>{snippet}</p>
      </ResultItem>
    );
  }) : null;

  switch(error) {
    case 1:
      return <p>Enter Search To Begin.</p>;
    case 2:
      return <p>Something wen't wrong.</p>
    default:
      break;
  }

  if (loading) {
    return (
      <Loader>
        <BeatLoader size={25} color="#0073aa" />
      </Loader>
    );
  }

  return <ResultContainer>{ displayResults }</ResultContainer>
}

export default SearchResults;
