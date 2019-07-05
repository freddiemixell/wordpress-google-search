function SearchBox(props) {

  const { query, handleChange, searchSite } = props;

  return (
    <form onSubmit={searchSite}>
      <input
        type='text'
        name="search-box"
        placeholder="Searching..."
        defaultValue={query}
        onChange={handleChange}
      />
      <input type="submit" />
    </form>
  )
          
}

export default SearchBox;
