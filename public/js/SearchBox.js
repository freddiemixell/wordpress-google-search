import styled from 'styled-components';

const Form = styled.form.attrs((props) => ({
  role: "search",
  method: "post",
  className: "search-form",
  onSubmit: props.searchSite
}))`
  padding-bottom: 20px;
`;

const SearchInput = styled.input.attrs((props) => ({
  type:'search',
  title:"Search for:",
  className:"search-field",
  name:"search-input",
  defaultValue: props.query,
  onChange: props.handleChange,
}))``;

const Submit = styled.input.attrs(() => ({
  className: "search-field",
  type: "submit",
  value: "Search"
}))`
  margin-left: 5px;
`;

function SearchBox(props) {

  return (
    <Form {...props}>
      <label>
      <span className="screen-reader-text">Search for: </span>
        <SearchInput {...props} />
      </label>
      <Submit />
    </Form>
  )
          
}

export default SearchBox;
