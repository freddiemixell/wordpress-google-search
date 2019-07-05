function SearchInfo(props) {

  const searchTime = props.results !== null
    ? props.results.searchInformation.formattedSearchTime
    : '';

  const totalResults = props.results !== null
    ? props.results.searchInformation.formattedTotalResults
    : '';

  const results = totalResults ? `${totalResults} results` : '';

  const time = searchTime ? `(${searchTime} seconds)` : '';
  
  return (
    <p>{results} {time}</p>
  );
}

export default SearchInfo;
