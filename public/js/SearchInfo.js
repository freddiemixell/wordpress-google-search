function SearchInfo(props) {

  const searchTime = props.results !== null
    ? props.results.searchInformation.formattedSearchTime
    : '';

  const totalResults = props.results !== null
    ? props.results.searchInformation.formattedTotalResults
    : '';

  const results = totalResults ? `${totalResults} results` : '0 results';

  const time = searchTime ? `(${searchTime} seconds)` : '(0 seconds)';
  
  return (
    <p>{results} {time}</p>
  );
}

export default SearchInfo;
