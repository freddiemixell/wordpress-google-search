
function SearchResults(props) {
  console.log(props);
  const results = props.results !== null
    ? props.results.items
    : [];

  const displayResults =  (typeof results !== 'undefined' && results.length > 0 ) ? results.map((result) => {
    const { title, link, displayLink, snippet } = result
    return (
      <li key={link}>
        <a href={link}>{title}</a>
        <p>{displayLink}</p>
        <p>{snippet}</p>
      </li>
    );
  }) : null;

  return <ul>{ displayResults }</ul>
}

export default SearchResults;
