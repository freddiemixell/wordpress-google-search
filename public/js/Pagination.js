import styled from 'styled-components';

const { Fragment } = wp.element;

const Button = styled.button.attrs(() => ({
  type: "button",
}))``;

function Pagination(props) {
  let nextIndex = null;
  let prevIndex = null;
  
  // Finding Next Page Start Index
  if (props.results !== null && typeof props.results.queries.nextPage !== 'undefined') {
    nextIndex = props.results.queries.nextPage[0].startIndex;
    console.log(nextIndex);
  }

  // Finding Prev Page Start Index
  if (props.results !== null && typeof props.results.queries.previousPage !== 'undefined') {
    prevIndex = props.results.queries.previousPage[0].startIndex;
    console.log(prevIndex);
  }

  return (
    <Fragment>
      { prevIndex !== null
       ? <Button>&lt;</Button>
       : null
      }
      { nextIndex !== null
       ? <Button>&gt;</Button>
       : null
      }
    </Fragment>
  );
}

export default Pagination;
